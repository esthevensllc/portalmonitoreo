<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Controllers;

use App\Modules\KPI_CM\CCS_CV_TEMT\Services\FindCCS_CV_TEMT;

class FindCCS_CV_TEMTController
{
    private $service;

    public function __construct(FindCCS_CV_TEMT $service)
    {
        $this->service = $service;
    }

    public function __invoke($id)
    {
        try {
            $resp = $this->service->__invoke($id);
            return response()->json($resp->toArray());
        } catch (\App\Modules\KPI_CM\CCS_CV_TEMT\Entity\Exceptions\CCS_CV_TEMT_NotFound $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
