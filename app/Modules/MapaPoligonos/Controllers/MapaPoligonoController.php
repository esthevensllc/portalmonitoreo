<?php

namespace App\Modules\MapaPoligonos\Controllers;

use App\Modules\MapaPoligonos\Services\CreateMapaPoligono;
use App\Modules\MapaPoligonos\Services\GetMapaPoligonos;
use Illuminate\Http\Request;

class MapaPoligonoController
{
    private $createService;
    private $getService;

    public function __construct(CreateMapaPoligono $createService, GetMapaPoligonos $getService)
    {
        $this->createService = $createService;
        $this->getService = $getService;
    }

    public function get(){
        $data = $this->getService->__invoke()->getData();
        return response()->json($data);
    }

    public function create(Request $request){
        $resp = $this->createService->__invoke($request->all());
        if($resp->passes()){
            return response()->json($resp->toArray());
        }
        return response()->json($resp->toArray(), 400);
    }
}
