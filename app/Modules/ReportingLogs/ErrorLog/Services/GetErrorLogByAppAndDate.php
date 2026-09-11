<?php

namespace App\Modules\ReportingLogs\ErrorLog\Services;

use App\Modules\ReportingLogs\ErrorLog\Repository\Oracle\OraErrorLogRepository;

class GetErrorLogByAppAndDate
{
    private $repo;

    public function __construct(OraErrorLogRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($app_id, $date)
    {
        return $this->repo->getByAppIdAndDate($app_id, $date);
    }
}
