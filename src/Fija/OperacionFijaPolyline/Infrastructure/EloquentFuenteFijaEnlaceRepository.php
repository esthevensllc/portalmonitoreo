<?php

namespace AMovil\Fija\OperacionFijaPolyline\Infrastructure;

use AMovil\Fija\OperacionFijaPolyline\Domain\FuenteFijaEnlace;
use AMovil\Fija\OperacionFijaPolyline\Domain\FuenteFijaEnlaceRepository;

class EloquentFuenteFijaEnlaceRepository implements FuenteFijaEnlaceRepository
{
    public function create(string $path): FuenteFijaEnlace
    {
        $model = new FuenteFijaEnlaceModel();
        $model->path = $path;
        $model->save();
        return $model->toDomain();
    }

    public function delete($id): void{
        $model = FuenteFijaEnlaceModel::find($id);
        $model->delete();
    }

    public function get(){
        return FuenteFijaEnlaceModel::get();
    }
}
