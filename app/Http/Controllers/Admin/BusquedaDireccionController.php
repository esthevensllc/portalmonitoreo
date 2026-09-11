<?php

namespace App\Http\Controllers\Admin;

use App\Traits\DB\ProcedureTrait;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PDO;

class BusquedaDireccionController
{
    use ProcedureTrait;

    public function view($tracingID)
    {
        $title = "FACTIBILIDAD | Factiblidad | Buscar Dirección";
        $idTransaccion = $this->getIdTransaccion();
        $mapUrl = "https://geopoint.analytics.pe/ClaroGPEWFrnt/?Idtransaccion={$idTransaccion}";
        $token = null;
        try {
            $token = $this->getToken();
            $mapUrl = "https://geopoint.analytics.pe/ClaroGPEWFrnt/?Idtransaccion={$idTransaccion}&Authorization={$token}";
        } catch (\Throwable $th) {
            // return $th->getMessage();
        }
        $username = backpack_user()->username;
        return view("backpack::busqueda_direccion", compact("tracingID", "title", "mapUrl", "username"));
    }

    private function getIdTransaccion(){
        $username = backpack_user()->username;
        $usuario = str_pad($username, 12, "0", STR_PAD_LEFT);
        $time = (new DateTime())->format("Ymd.His");
        return base64_encode("PMONITOREO.{$usuario}.{$time}");
    }

    private function getToken(){
        // URL de destino
        $url = 'https://gpservices.analytics.pe/GeoPointPlatformService/BASecurityMethods/api/TokenFromApiKeypackage';
        $header = "Authorization: Bearer " . env("GEOS_TOKEN");

        // Configuración del proxy
        $proxy = 'http://claro-proxy:80';
    
        // Inicializa cURL
        $curl = curl_init();
    
        // Establece la URL y otras opciones necesarias
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [$header]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ""); // Enviar un cuerpo de solicitud vacío
    
        // Configura el proxy
        curl_setopt($curl, CURLOPT_PROXY, $proxy);
    
        // Si el proxy requiere autenticación
        // curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username:$password");
    
        // Realiza la solicitud y obtén la respuesta
        $response = curl_exec($curl);
    
        // Manejo de errores
        if ($response === false) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new Exception("cURL Error: $error_msg");
        }
    
        // Cierra la conexión cURL
        curl_close($curl);
    
        // Decodificar la respuesta JSON
        $response = json_decode($response);
    
        // Verificar que la respuesta JSON se decodificó correctamente
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Decode Error: " . json_last_error_msg());
        }
    
        // Verificar que el token esté presente en la respuesta
        if (!isset($response->tokenpk)) {
            throw new Exception("Token not found in response.");
        }
    
        return $response->tokenpk;
    }

    public function getCobertura(Request $request)
    {
        $username = backpack_user()->username;
        $procedure = "begin PKG_Inv_Factibilidad_Venta.sp_consultaCobertura(:cuentaUser, :application, :tipo, :longitud, :latitud, :resultado); end;";
        $data = $this->executeProcedure($procedure, [
            "cuentaUser" => ["value" => $username, "type" => PDO::PARAM_STR],
            "application" => ["value" => "PMONITOREO", "type" => PDO::PARAM_STR],
            "tipo" => ["value" => $request->get("tipo"), "type" => PDO::PARAM_STR],
            "longitud" => ["value" => $request->get("longitud"), "type" => PDO::PARAM_STR],
            "latitud" => ["value" => $request->get("latitud"), "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);

        for ($i=0; $i < count($data); $i++) { 
            if($data[$i]["PARAMETROS"] !== null){
                if($data[$i]["SERVICIO"] === "VERTICAL"){
                    $data[$i]["PARAMETROS"] = json_decode("[".$data[$i]["PARAMETROS"]."]");
                }else{
                    $parametros = json_decode($data[$i]["PARAMETROS"]);
                    if($parametros === null){
                        $data[$i]["PARAMETROS"] = json_decode("[".$data[$i]["PARAMETROS"]."]");
                    }else{
                        $data[$i]["PARAMETROS"] = $parametros;
                    }
                    if(is_array($data[$i]["PARAMETROS"])){
                        $data[$i]["PARAMETROS"] = $this->filterPlanoServicio($data[$i]["PARAMETROS"]);
                    }
                }
            }
        }

        return response()->json($data);
    }

    public function filterPlanoServicio($planos){
        $first = count($planos) > 0 ? $planos[0] : null;
        $planoS = null;
        $planoR = null;
        foreach($planos as $row){
            if(str_ends_with($row->Plano, "-S") && $planoS !== null){
                $planoS = $row;
            }
            if(str_ends_with($row->Plano, "-R") && $planoR !== null){
                $planoR = $row;
            }
        }
        if($planoR !== null){
            return $planoR;
        }else if($planoS !== null){
            return $planoS;
        }else{
            return $first;
        }
    }
}
