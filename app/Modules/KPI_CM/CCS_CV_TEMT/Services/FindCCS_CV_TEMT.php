<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Services;

use App\Modules\KPI_CM\CCS_CV_TEMT\Entity\Exceptions\CCS_CV_TEMT_NotFound;
use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMT_Repository;
use App\Modules\Shared\Services\Response;

class FindCCS_CV_TEMT
{
    private $repo;

    public function __construct(CCS_CV_TEMT_Repository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($id): Response
    {
        $data = $this->repo->find($id);
        if($data === null){
            throw new CCS_CV_TEMT_NotFound("CCS_CV_TEMT no econtrado");
        }
        return new Response([], $data);
    }
}
