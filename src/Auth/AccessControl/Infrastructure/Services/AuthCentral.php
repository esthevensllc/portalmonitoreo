<?php

namespace AMovil\Auth\AccessControl\Infrastructure\Services;

use AMovil\Auth\AccessControl\Domain\AuthService;
use AMovil\Shared\Domain\Session\Session;
use Datetime;

class AuthCentral implements AuthService
{
    private $url_auth;
    private $session_name;
    private $apiKey;
    private $secret;
    private $redirect_to;
    private $redirect_to_encoded;
    private $error = null;
    private $serssionService;

    public function __construct($url_auth, $session_name, $apiKey, $secret, $redirect_to, Session $serssionService)
    {
        $this->url_auth = $url_auth;
        $this->session_name = $session_name;
        $this->apiKey = $apiKey;
        $this->secret = $secret;
        $this->redirect_to = $redirect_to;
        $this->redirect_to_encoded = urlencode($redirect_to);
        $this->serssionService = $serssionService;
    }

    private function getUrlLogin()
    {
        return "$this->url_auth?action=remoteAuthenticacion&service=$this->redirect_to_encoded&apiKey=$this->apiKey";
    }

    private function getUrlLogout()
    {
        return "$this->url_auth?action=remoteLogoutService&service=$this->redirect_to_encoded&apiKey=$this->apiKey";
    }

    private function getUrlCheckRemoteLogin()
    {
        return "$this->url_auth?action=checkRemoteLogin";
    }

    private function getUrlSimpleLogin()
    {
        return "$this->url_auth?action=simpleLogin";
    }

    private function getUrlRemoteLogin()
    {
        return "$this->url_auth?action=remoteAuthenticacion&service=$this->redirect_to_encoded&apiKey=$this->apiKey";
    }

    /**
     * @param $username
     * @param $password
     * @return array
     */
    public function login(string $username, string $password)
    {

        $params = array(
            'login' => array(
                'username' => $username,
                'password' => $password,
            ),
            'apiKey' => $this->apiKey,
            'secret' => $this->secret,
            'service' => $this->redirect_to_encoded
        );

        //$request = $this->buildCurlRequest($this->getUrlSimpleLogin(), $params);
        $request = $this->buildCurlRequest($this->getUrlRemoteLogin(), $params);

        $response["response"] = json_decode($request["response"], true);

        if(array_key_exists("location", $request["headers"])){
            if(count($request["headers"]["location"]) > 0){
                $location = $request["headers"]["location"][0];
                $ticketID = str_replace("{$this->redirect_to}?ticketID=", "", $location);
                $request["headers"]["location"][] = $ticketID;

                $this->validateSession(["ticketID" => $ticketID]);
            }
        }else{
            $this->serssionService->setAll([
                $this->session_name.'__username' => null,
                $this->session_name.'__expireDate' => null,
            ]);
        }

        return $request;
    }

    public function validateSession(array $params = [])
    {
        $ticketID = null;
        if(array_key_exists('ticketID', $params)){
            $ticketID = $params['ticketID'];
        }
        if($ticketID !== null){
            $result = $this->checkRemoteLogin($ticketID);
            $is_auth = (isset($result['result']) && $result['result']);
            if($is_auth){
                $this->serssionService->setAll([
                    $this->session_name.'__username' => $result['userInfo']['username'],
                    $this->session_name.'__expireDate' => $result['expireDate']+3600,
                ]);
            }else{
                $this->serssionService->setAll([
                    $this->session_name.'__username' => null,
                    $this->session_name.'__expireDate' => null,
                ]);
            }
            return $is_auth;
        }
        if($this->serssionService->get($this->session_name.'__username') !== null){
            $expireTimestamp = $this->serssionService->get($this->session_name.'__expireDate');
            $now = new DateTime();
            $expireDate = new DateTime();
            $expireDate->setTimestamp($expireTimestamp);

            return $now < $expireDate;
        }
        return false;
    }

    public function logout()
    {
        $this->serssionService->invalidate();
    }

    public function getUserIdentifier()
    {
        return $this->serssionService->get($this->session_name.'__username');
    }

    public function getConfig($name)
    {
        switch ($name) {
            case 'url_login':
                return $this->getUrlLogin();
                break;
            case 'url_logout':
                return $this->getUrlLogout();
                break;
            default:
                return null;
                break;
        }
    }

    private function checkRemoteLogin($ticketID){
        $response = $this->buildCurlRequest($this->getUrlCheckRemoteLogin(), array(
            'secret' => $this->secret, 'ticketID' => $ticketID
        ));
        $result = json_decode($response["response"], true);
        return $result;
    }

    private function buildCurlRequest($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $headers = [];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // this function is called by curl for each header received
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers){
            $len = strlen($header);
            $header = explode(':', $header, 2);
            //var_dump($header); echo "<br>";
            if (count($header) < 2){
                return $len;
            } // ignore invalid headers

            $headers[strtolower(trim($header[0]))][] = trim($header[1]);
            
            return $len;
        });

        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        $response = curl_exec($ch);
        rewind($verbose);
        if ($response === FALSE) {
            $this->error = curl_error($ch);

        }
        curl_close($ch);

        return ["headers" => $headers, "response" => $response];
    }
}
