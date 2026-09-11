<?php

namespace App\Modules\KPI_CM\CVM\Services;

use App\Modules\KPI_CM\CVM\Exports\CM_CVMExport;
use App\Modules\KPI_CM\CVM\Repository\CM_CVM_Repository;
use App\Modules\Shared\Services\Response;
use Carbon\Carbon;

class ExportCM_CVM
{
    private $repo;

    public function __construct(CM_CVM_Repository $repo)
    {
        $this->repo = $repo;    
    }

    public function __invoke(): Response
    {
        $now = Carbon::now();
        $collection = $this->repo->get();
        $data = (new CM_CVMExport($collection))->download('CM_CVM_'.$now->format('Ymd').'.xlsx');
        return new Response([], $data);
    }

    public function exportCollection($collection): Response
    {
        $now = Carbon::now();
        $data = (new CM_CVMExport($collection))->download('CM_CVM_'.$now->format('Ymd').'.xlsx');
        return new Response([], $data);
    }
}
