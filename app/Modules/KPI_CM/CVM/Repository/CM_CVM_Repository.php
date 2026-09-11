<?php

namespace App\Modules\KPI_CM\CVM\Repository;

use App\Modules\Shared\Repository\BaseRepository;
use DB;

class CM_CVM_Repository extends BaseRepository
{
    public function create($data){
        $model = new CM_CVM();
        $model->region = $this->mapField($data, 'region');
        $model->encargados = $this->mapField($data, 'encargados');
        $model->ubigeo = $this->mapField($data, 'ubigeo');
        $model->departamento = $this->mapField($data, 'departamento');
        $model->ccpp = $this->mapField($data, 'ccpp');
        $model->indicador = $this->mapField($data, 'indicador');
        $model->tecnologia = $this->mapField($data, 'tecnologia');
        $model->dl_3g = $this->mapField($data, 'dl_3g');
        $model->dl_4g = $this->mapField($data, 'dl_4g');
        $model->ul_3g = $this->mapField($data, 'ul_3g');
        $model->ul_4g = $this->mapField($data, 'ul_4g');
        $model->cm_enviado = $this->mapField($data, 'cm_enviado');
        $model->fecha = $this->mapField($data, 'fecha');
        $model->fecha_limite = $this->mapField($data, 'fecha_limite');
        $model->periodo = $this->mapField($data, 'periodo');
        $model->multa = $this->mapField($data, 'multa');
        $model->grupo_multa = $this->mapField($data, 'grupo_multa');
        $model->actividad = $this->mapField($data, 'actividad');
        $model->fecha_actividad = $this->mapField($data, 'fecha_actividad');
        $model->estado = $this->mapField($data, 'estado');
        $model->acta_levantada = $this->mapField($data, 'acta_levantada');
        $model->comentarios_red = $this->mapField($data, 'comentarios_red');
        $model->enviado_osiptel = $this->mapField($data, 'enviado_osiptel');
        $model->fecha_env_osiptel = $this->mapField($data, 'fecha_env_osiptel');
        $model->comentarios_rg = $this->mapField($data, 'comentarios_rg');
        $model->acta_archivo = $this->mapField($data, 'acta_archivo');
        $model->save();
    }

    public function update($data){
        $model = CM_CVM::find($this->mapField($data, 'id'));
        $model->region = $this->mapField($data, 'region');
        $model->encargados = $this->mapField($data, 'encargados');
        $model->ubigeo = $this->mapField($data, 'ubigeo');
        $model->departamento = $this->mapField($data, 'departamento');
        $model->ccpp = $this->mapField($data, 'ccpp');
        $model->indicador = $this->mapField($data, 'indicador');
        $model->tecnologia = $this->mapField($data, 'tecnologia');
        $model->dl_3g = $this->mapField($data, 'dl_3g');
        $model->dl_4g = $this->mapField($data, 'dl_4g');
        $model->ul_3g = $this->mapField($data, 'ul_3g');
        $model->ul_4g = $this->mapField($data, 'ul_4g');
        $model->cm_enviado = $this->mapField($data, 'cm_enviado');
        $model->fecha = $this->mapField($data, 'fecha');
        $model->fecha_limite = $this->mapField($data, 'fecha_limite');
        $model->periodo = $this->mapField($data, 'periodo');
        $model->multa = $this->mapField($data, 'multa');
        $model->grupo_multa = $this->mapField($data, 'grupo_multa');
        $model->actividad = $this->mapField($data, 'actividad');
        $model->fecha_actividad = $this->mapField($data, 'fecha_actividad');
        $model->estado = $this->mapField($data, 'estado');
        $model->acta_levantada = $this->mapField($data, 'acta_levantada');
        $model->comentarios_red = $this->mapField($data, 'comentarios_red');
        $model->enviado_osiptel = $this->mapField($data, 'enviado_osiptel');
        $model->fecha_env_osiptel = $this->mapField($data, 'fecha_env_osiptel');
        $model->comentarios_rg = $this->mapField($data, 'comentarios_rg');
        // $model->acta_archivo = $this->mapField($data, 'acta_archivo');
        $model->save();
    }

    public function updateActaArchivo($id, $acta_archivo){
        $model = CM_CVM::find($id);
        $model->acta_archivo = $acta_archivo;
        $model->save();
    }

    public function get(){
        return CM_CVM::get();
    }

    public function find($id){
        $model = DB::table(CM_CVM::table())->where('id', $id)->first();
        if($model !== null){
            $model->acta_archivo_url = $model->acta_archivo !== null ? asset('cm-cvm/files/'.$model->acta_archivo) : null;
        }
        return $model;
    }
}
