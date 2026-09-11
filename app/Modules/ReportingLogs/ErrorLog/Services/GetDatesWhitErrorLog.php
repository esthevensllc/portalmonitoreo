<?php

namespace App\Modules\ReportingLogs\ErrorLog\Services;

use App\Modules\ReportingLogs\ErrorLog\Repository\Oracle\OraErrorLogRepository;

class GetDatesWhitErrorLog
{
    private $repo;

    public function __construct(OraErrorLogRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke()
    {
        return $this->repo->getDatesWhitErrorLogGroupedByAppId();
    }
}
