<?php

namespace App\Modules\KPI_CM\CVM\Services;

use App\Modules\KPI_CM\CVM\Entity\Exceptions\CM_CVM_NotFound;
use App\Modules\KPI_CM\CVM\Repository\CM_CVM_Repository;
use App\Modules\Shared\Services\Response;

class FindCM_CVM
{
    private $repo;

    public function __construct(CM_CVM_Repository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($id): Response
    {
        $data = $this->repo->find($id);
        if($data === null){
            throw new CM_CVM_NotFound("CM_CVM no econtrado");
        }
        return new Response([], $data);
    }
}
