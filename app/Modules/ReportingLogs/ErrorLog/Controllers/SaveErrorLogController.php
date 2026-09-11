<?php

namespace App\Modules\ReportingLogs\ErrorLog\Controllers;

use App\Modules\Auth\Entities\Operations;
use App\Modules\Auth\Services\VerifyToken;
use App\Modules\ReportingLogs\ErrorLog\Services\SaveErrorLog;
use Illuminate\Http\Request;

class SaveErrorLogController
{
    private SaveErrorLog $service;
    private VerifyToken $verifyToken;

    public function __construct(SaveErrorLog $service, VerifyToken $verifyToken)
    {
        $this->service = $service;
        $this->verifyToken = $verifyToken;
    }

    public function __invoke(Request $request)
    {
        try {
            $resp = $this->authMiddleware($request->input('token'));
            if($resp->fails()){
                return response()->json($resp->getErrors(), $resp->getData());
            }

            $response = $this->service->__invoke($request->all());

            if($response->passes()){
                return response()->json($response->toArray());
            }
            return response()->json($response->toArray(), 400); 
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function authMiddleware($token){
        return $this->verifyToken->__invoke($token, Operations::SAVE_ERROR_LOG);
    }
}
