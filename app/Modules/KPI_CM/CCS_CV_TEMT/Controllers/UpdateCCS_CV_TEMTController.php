<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Controllers;

use App\Modules\KPI_CM\CCS_CV_TEMT\Services\UpdateCCS_CV_TEMT;
use Illuminate\Http\Request;

class UpdateCCS_CV_TEMTController
{
    private $service;
    public function __construct(UpdateCCS_CV_TEMT $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request)
    {
        // return $request->all();
        $resp = $this->service->__invoke($request->all());
        if($resp->passes()){
            $this->service->uploadActaArchivo($request);
            return response()->json($resp->toArray());
        }
        return response()->json($resp->toArray(), 400);
    }
}
