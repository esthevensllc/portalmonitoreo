<?php

namespace App\Modules\ReportingLogs\ErrorLog\Services;

use App\Modules\ReportingLogs\ErrorLog\Repository\Oracle\OraErrorLogRepository;
use App\Modules\Shared\Services\Response;
use Validator;

class DeleteErrorLogByAppIdAndDate
{
    private $repo;

    public function __construct(OraErrorLogRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($app_id, $date): Response
    {
        $validator = Validator::make([
            'app_id' => $app_id,
            'date' => $date
        ], [
            'app_id' => ['required', 'exists:padm_replogs_notification,app_id'],
            'date' => ['required', 'date']
        ]);
        if($validator->passes()){
            $this->repo->deleteByAppIdAndDate($app_id, $date);
            return new Response([]);
        }
        return new Response($validator->errors()->messages());
    }
}
