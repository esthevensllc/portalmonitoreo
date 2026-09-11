<?php

namespace App\Modules\ReportingLogs\ErrorLog\Controllers;

use App\Modules\Auth\Entities\Operations;
use App\Modules\Auth\Services\VerifyToken;
use App\Modules\Notifications\Repository\NotificationRepository;
use App\Modules\ReportingLogs\ErrorLog\Services\GetDatesWhitErrorLog;
use App\Modules\ReportingLogs\ErrorLog\Services\GetDatesWhitLogs;
use App\Modules\ReportingLogs\ErrorLog\Services\GetErrorLogByAppAndDate;
use Illuminate\Http\Request;

class GetErrorLogController
{
    private $service;
    private $getDatesWhitErrorLog;
    private $notificationRepo;
    private $verifyToken;

    public function __construct(
        GetErrorLogByAppAndDate $service,
        GetDatesWhitErrorLog $getDatesWhitErrorLog,
        NotificationRepository $notificationRepo,
        VerifyToken $verifyToken
    ){
        $this->service = $service;
        $this->getDatesWhitErrorLog = $getDatesWhitErrorLog;
        $this->notificationRepo = $notificationRepo;
        $this->verifyToken = $verifyToken;
    }

    public function __invoke(Request $request, $app_id, $date)
    {
        $resp = $this->authMiddleware($request->bearerToken());
        if($resp->fails()){
            return response()->json($resp->getErrors(), $resp->getData());
        }

        $resp = $this->service->__invoke($app_id, $date);
        return response()->json($resp);
    }

    public function errorLogBackFront(Request $request){
        // return bcrypt('get-error-log');
        $resp = $this->authMiddleware($request->bearerToken());
        if($resp->fails()){
            return response()->json($resp->getErrors(), $resp->getData());
        }

        return response()->json([
            'apps' => $this->notificationRepo->get(),
            'datesWhitErrorLog' => $this->getDatesWhitErrorLog->__invoke(),
        ]);
    }

    public function authMiddleware($token){
        return $this->verifyToken->__invoke($token, Operations::GET_ERROR_LOG);
    }
}
