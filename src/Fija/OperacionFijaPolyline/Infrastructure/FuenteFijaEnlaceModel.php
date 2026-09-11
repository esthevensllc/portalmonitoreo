<?php

namespace AMovil\Fija\OperacionFijaPolyline\Infrastructure;

use AMovil\Fija\OperacionFijaPolyline\Domain\FuenteFijaEnlace;
use Illuminate\Database\Eloquent\Model;

class FuenteFijaEnlaceModel extends Model
{
    protected $table = "operaciones_fija_fuente_enlace";

    public static function fromDomain(FuenteFijaEnlace $domain): self {
        $model = new FuenteFijaEnlaceModel();
        if($domain->getId() !== null){
            $model = FuenteFijaEnlaceModel::find($domain->getId());
        }
        $model->path = $domain->getPath();
        return $model;
    }

    public function toDomain(): FuenteFijaEnlace {
        return new FuenteFijaEnlace($this->id, $this->path);
    }
}
