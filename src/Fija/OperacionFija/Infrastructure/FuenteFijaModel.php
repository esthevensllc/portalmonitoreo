<?php

namespace AMovil\Fija\OperacionFija\Infrastructure;

use AMovil\Fija\OperacionFija\Domain\FuenteFija;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FuenteFijaModel extends Model
{
    protected $table = "operaciones_fija_fuente";

    public static function fromDomain(FuenteFija $domain): self {
        $model = new FuenteFijaModel();
        if($domain->getId() !== null){
            $model = FuenteFijaModel::find($domain->getId());
        }
        $model->plano = $domain->getPlano();
        $model->tipo_nodo = $domain->getTipoNodo();
        $model->tipo_fuente = $domain->getTipoFuente();
        $model->ubicado_en = $domain->getUbicadoEn();
        $model->tiene_baterias = $domain->getTieneBaterias();
        $model->anio_fab = $domain->getAnioFabricacion();
        $model->tipo_respaldo = $domain->getTipoRespaldo();
        $model->amp = $domain->getAmp();
        $model->candado = $domain->getCandado();
        $model->barra = $domain->getBarra();
        $model->seguro_h = $domain->getSeguroH();
        $model->seguro_baterias = $domain->getSeguroBaterias();
        $model->fecha_manto = $domain->getFechaManto() !== null ? $domain->getFechaManto()->format("Y-m-d H:i:s") : null;
        $model->anio_manto = $domain->getAnioManto();
        $model->mes_manto = $domain->getMesManto();
        $model->referido = $domain->getReferido();
        $model->region = $domain->getRegion();
        $model->distrito = $domain->getDistrito();
        $model->segmento_urbano = $domain->getSegmentoUrbano();
        $model->gestion_campo = $domain->getGestionCampo();
        $model->nivel_de_seguridad = $domain->getNivelSeguridad();
        $model->comentario = $domain->getComentario();
        $model->latitud = $domain->getLatitud();
        $model->longitud = $domain->getLongitud();
        return $model;
    }

    public function toDomain(){
        $fuente = new FuenteFija(
            $this->id,
            $this->plano,
            $this->tipo_nodo,
            $this->tipo_fuente,
            $this->ubicado_en,
            $this->tiene_baterias,
            $this->anio_fab,
            $this->tipo_respaldo,
            $this->amp,
            $this->candado,
            $this->barra,
            $this->seguro_h,
            $this->seguro_baterias,
            $this->fecha_manto !== null ? DateTime::createFromFormat("Y-m-d H:i:s", $this->fecha_manto) : null,
            $this->anio_manto,
            $this->mes_manto,
            $this->referido,
            $this->region,
            $this->distrito,
            $this->segmento_urbano,
            $this->gestion_campo,
            $this->nivel_de_seguridad,
            $this->comentario,
            $this->latitud,
            $this->longitud,
        );
        $fuente->setImages($this->getImages());
        return $fuente;
    }

    public function getImages(): array {
        return DB::table("operaciones_fija_fuente_imagen")
        ->where("fuente_id", $this->id)
        ->get()
        ->map(function($row){
            return $row->imagen;
        })
        ->toArray();
    }

    public function updateImages(array $images) {
        DB::table("operaciones_fija_fuente_imagen")
        ->where("fuente_id", $this->id)
        ->delete();

        $imagesMapped = [];
        foreach($images as $image){
            $imagesMapped[] = ["fuente_id" => $this->id, "imagen" => $image, "created_at" => new DateTime()];
        }

        return DB::table("operaciones_fija_fuente_imagen")->insert($imagesMapped);
    }
}
