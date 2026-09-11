<?php

namespace App\Modules\ReportingLogs\ErrorLog\Services;

use App\Modules\Notifications\Services\SendNotification;
use App\Modules\ReportingLogs\ErrorLog\Entities\ErrorLevel;
use App\Modules\ReportingLogs\ErrorLog\Repository\Oracle\OraErrorLogRepository;
use App\Modules\Shared\Services\Response;
use Validator;
use Carbon\Carbon;

class SaveErrorLog
{
    private $repo;
    private $notificationService;

    public function __construct(OraErrorLogRepository $repo, SendNotification $notificationService)
    {
        $this->repo = $repo;
        $this->notificationService = $notificationService;
    }

    public function __invoke($data)
    {
        $date = Carbon::now()->format('Y-m-d');
        $occurred_on = Carbon::now()->format('Y-m-d H:i:s');

        $alldata = array_merge($data, [
            'date_occurred' => $date,
            'occurred_on' => $occurred_on,
        ]);

        if(!isset($alldata['e_level'])){
            $alldata['e_level'] = ErrorLevel::ERROR;
        }

        $validator = Validator::make($alldata, [
            'app_id' => ['required', 'exists:padm_replogs_notification,app_id'],
            'e_level' => ['required', 'integer'],
            'env' => ['required'],
            'date_occurred' => ['required'],
            'message' => ['required'],
            'stacktrace' => ['required'],
            'occurred_on' => ['required'],
        ]);

        if($validator->passes()){
            $this->repo->save($alldata);

            if(ErrorLevel::isError($alldata['e_level']) && $alldata['env'] === 'produccion'){
                $this->notificationService->__invoke([
                    'app_id' => $data['app_id'],
                    'subject' => ' '.$data['env'],
                    'message' => ' '.$data['message'],
                ]);
            }

            return new Response($validator->errors()->messages());
        }
        return new Response($validator->errors()->messages());
    }
}
