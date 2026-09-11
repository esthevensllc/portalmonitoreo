<?php

namespace App\Modules\KPI_CM\CVM\Controllers;

use App\Modules\KPI_CM\CVM\Services\FindCM_CVM;

class FindCM_CVMController
{
    private $service;

    public function __construct(FindCM_CVM $service)
    {
        $this->service = $service;
    }

    public function __invoke($id)
    {
        try {
            $resp = $this->service->__invoke($id);
            return response()->json($resp->toArray());
        } catch (\App\Modules\KPI_CM\CVM\Entity\Exceptions\CM_CVM_NotFound $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Throwable $th) {
            return response()->json([], 500);
        }
    }
}
