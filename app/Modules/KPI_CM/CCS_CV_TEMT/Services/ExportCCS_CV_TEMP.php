<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Services;

use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMT_Repository;
use App\Modules\Shared\Services\Response;
use Carbon\Carbon;

class ExportCCS_CV_TEMP
{
    private $repo;

    public function __construct(CCS_CV_TEMT_Repository $repo)
    {
        $this->repo = $repo;    
    }

    public function __invoke(): Response
    {
        $now = Carbon::now();
        $collection = $this->repo->get();
        $data = (new \App\Modules\KPI_CM\CCS_CV_TEMT\Exports\CCS_VS_TEMTExport($collection))
            ->download('CCS_VS_TEMT_'.$now->format('Ymd').'.xlsx');
        return new Response([], $data);
    }

    public function exportCollection($collection): Response
    {
        $now = Carbon::now();
        $data = (new \App\Modules\KPI_CM\CCS_CV_TEMT\Exports\CCS_VS_TEMTExport($collection))
            ->download('CCS_VS_TEMT_'.$now->format('Ymd').'.xlsx');
        return new Response([], $data);
    }
}
