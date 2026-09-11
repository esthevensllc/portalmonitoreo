<?php

namespace App\Modules\KPI_CM\CVM\Controllers;

use App\Modules\KPI_CM\CVM\Services\UpdateCM_CVM;
use Illuminate\Http\Request;

class UpdateCM_CVMController
{
    private $service;
    public function __construct(UpdateCM_CVM $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request)
    {
        $resp = $this->service->__invoke($request->all());
        if($resp->passes()){
            $this->service->uploadActaArchivo($request);
            return response()->json($resp->toArray());
        }
        return response()->json($resp->toArray(), 400);
    }
}
