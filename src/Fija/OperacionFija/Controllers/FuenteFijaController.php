<?php

namespace AMovil\Fija\OperacionFija\Controllers;

use AMovil\Fija\OperacionFija\Services\FuenteFijaCreator;
use AMovil\Fija\OperacionFija\Services\FuenteFijaDeleter;
use AMovil\Fija\OperacionFija\Services\FuenteFijaExporter;
use AMovil\Fija\OperacionFija\Services\FuenteFijaFinder;
use AMovil\Fija\OperacionFija\Services\FuenteFijaUpdater;
use Illuminate\Http\Request;

class FuenteFijaController
{
    private $finder;
    private $creator;
    private $updater;
    private $deleter;
    private $exporter;

    public function __construct(
        FuenteFijaFinder $finder,
        FuenteFijaCreator $creator,
        FuenteFijaUpdater $updater,
        FuenteFijaDeleter $deleter,
        FuenteFijaExporter $exporter,
    ){
        $this->finder = $finder;
        $this->creator = $creator;
        $this->updater = $updater;
        $this->deleter = $deleter;
        $this->exporter = $exporter;
    }

    public function find($id)
    {
        try {
            $fuente = $this->finder->findById($id);
            return response()->json(["data" => $fuente->toArray()]);
        } catch (\AMovil\Fija\OperacionFija\Domain\Exceptions\FuenteFijaNotFound $th) {
            return response()->json(["message" => $th->getMessage()], 404);
        }
    }

    public function create(Request $request)
    {
        $response = $this->creator->__invoke($request->all());
        if($response->passes()){
            return response()->json($response->toArray() ,201);
        }
        return response()->json($response->toArray(), 400);
    }

    public function update(Request $request)
    {
        $response = $this->updater->__invoke($request->all());
        if($response->passes()){
            return response()->json($response->toArray());
        }
        return response()->json($response->toArray(), 400);
    }

    public function updateImages(Request $request)
    {
        $images = $request->file("images");
        $this->updater->updateImages(
            $request->input("id"),
            $images,
            $request->input("tipo_carga_imagen")
        );
        return response()->json([]);
    }

    public function delete($id)
    {
        try {
            $this->deleter->__invoke($id);
            return response()->json([]);
        } catch (\AMovil\Fija\OperacionFija\Domain\Exceptions\FuenteFijaNotFound $th) {
            return response()->json(["message" => $th->getMessage()], 404);
        }
    }

    public function export(){
        $response = $this->exporter->__invoke()->data();
        return response($response["content"], 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="'.$response["filename"].'"'
        ]);
    }
}
