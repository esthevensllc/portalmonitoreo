<?php

namespace AMovil\Fija\OperacionFijaPolyline\Controllers;

use AMovil\Fija\OperacionFijaPolyline\Services\FuenteFijaEnlaceFinder;
use AMovil\Fija\OperacionFijaPolyline\Services\FuenteFijaEnlaceWriter;
use Illuminate\Http\Request;

class OperacionFijaEnlaceController
{
    private $writer;
    private $finder;

    public function __construct(FuenteFijaEnlaceWriter $writer, FuenteFijaEnlaceFinder $finder)
    {
        $this->writer = $writer;
        $this->finder = $finder;
    }

    public function create(Request $request){
        $response = $this->writer->create($request->input("path"));
        return response()->json($response->data());
    }

    public function delete($id){
        $this->writer->delete($id);
        return response()->json([]);
    }

    public function get(){
        $data = $this->finder->get();
        return response()->json($data);
    }
}
