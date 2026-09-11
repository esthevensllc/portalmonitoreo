<?php

namespace App\Modules\Notifications\Controllers;

use App\Modules\Auth\Entities\Operations;
use App\Modules\Auth\Services\VerifyToken;
use App\Modules\Notifications\Services\SendNotification;
use Illuminate\Http\Request;

class SendNotificationController
{
    private SendNotification $service;
    private VerifyToken $verifyToken;

    public function __construct(SendNotification $service, VerifyToken $verifyToken)
    {
        $this->service = $service;
        $this->verifyToken = $verifyToken;
    }
    
    public function __invoke(Request $request){
        // return bcrypt('notification-smart');
        $this->authMiddleware($request->input('token'));
        // $header = $request->header('Authorization');
        $response = $this->service->__invoke($request->all());
        $status = $response->passes() ? 200 : 400;
        // return response()->json(['ok']);
        return response()->json($response->toArray(), $status);
    }

    public function authMiddleware($token){
        $resp = $this->verifyToken->__invoke($token, Operations::SAVE_ERROR_LOG);
        if($resp->fails()){
            return response()->json($resp->getErrors(), $resp->getData());
        }
    }
}
