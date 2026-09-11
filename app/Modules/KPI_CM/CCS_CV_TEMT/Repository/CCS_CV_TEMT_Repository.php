<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Repository;

use App\Modules\Shared\Repository\BaseRepository;
use DB;
use Illuminate\Support\Facades\Storage;

class CCS_CV_TEMT_Repository extends BaseRepository
{
    public function create($data): ?int {
        // dd($data);
        // $data['gsm'] = $this->mapField($data, 'gsm');
        // $data['umts'] = $this->mapField($data, 'umts');
        // $data['comentario_osiptel'] = $this->mapField($data, 'comentario_osiptel');
        // $data['fecha_real_mejora'] = $this->mapField($data, 'fecha_real_mejora');
        // $data['acciones_realizadas'] = $this->mapField($data, 'acciones_realizadas');
        // $data['acta_archivo'] = $this->mapField($data, 'acta_archivo');
        // $model = new CCS_CV_TEMP($data);
        $model = new CCS_CV_TEMP();
        $model->encargados = $this->mapField($data, 'encargados');
        $model->region = $this->mapField($data, 'region');
        $model->ubigeo = $this->mapField($data, 'ubigeo');
        $model->departamento = $this->mapField($data, 'departamento');
        $model->provincia = $this->mapField($data, 'provincia');
        $model->distrito = $this->mapField($data, 'distrito');
        $model->ccpp = $this->mapField($data, 'ccpp');
        $model->tecnologia = $this->mapField($data, 'tecnologia');
        $model->indicador = $this->mapField($data, 'indicador');
        $model->gsm = $this->mapField($data, 'gsm');
        $model->umts = $this->mapField($data, 'umts');
        $model->comentario_osiptel = $this->mapField($data, 'comentario_osiptel');
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
        return $model->id;
    }

    public function update($data){
        $model = CCS_CV_TEMP::find($data['id']);
        $model->encargados = $this->mapField($data, 'encargados');
        $model->region = $this->mapField($data, 'region');
        $model->ubigeo = $this->mapField($data, 'ubigeo');
        $model->departamento = $this->mapField($data, 'departamento');
        $model->provincia = $this->mapField($data, 'provincia');
        $model->distrito = $this->mapField($data, 'distrito');
        $model->ccpp = $this->mapField($data, 'ccpp');
        $model->tecnologia = $this->mapField($data, 'tecnologia');
        $model->indicador = $this->mapField($data, 'indicador');
        $model->gsm = $this->mapField($data, 'gsm');
        $model->umts = $this->mapField($data, 'umts');
        $model->comentario_osiptel = $this->mapField($data, 'comentario_osiptel');
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
        $model = CCS_CV_TEMP::find($id);
        $model->acta_archivo = $acta_archivo;
        $model->save();
    }

    public function find($id){
        $model = DB::table(CCS_CV_TEMP::table())->where('id', $id)->first();
        if($model !== null){
            $model->acta_archivo_url = $model->acta_archivo !== null ? asset('cm-ccs-cv-temt/files/'.$model->acta_archivo) : null;
        }
        return $model;
    }

    public function get() {
        return CCS_CV_TEMP::get();
    }
}
