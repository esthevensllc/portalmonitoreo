<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Services;

use App\Modules\KPI_CM\CCS_CV_TEMT\Entity\UpdateValidator;
use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMT_Repository;
use App\Modules\Shared\Services\Response;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class UpdateCCS_CV_TEMT
{
    private $validator;
    private $repo;

    public function __construct(CCS_CV_TEMT_Repository $repo)
    {
        $this->repo = $repo;
        $this->validator = new UpdateValidator;    
    }

    public function __invoke($data)
    {
        $this->validator->validate($data);
        if($this->validator->passes()){
            $this->repo->update($data);
            return new Response([], null);
        }
        return new Response($this->validator->errors(), null);
    }

    public function uploadActaArchivo(Request $request){
        $id = $request->input('id');
        $finded = $this->repo->find($id);
        $fileName = $finded->acta_archivo;
        if($finded !== null && $request->hasFile('acta_archivo')){
            if($fileName !== null){
                Storage::delete('cm_ccs_cv_temt/'.$fileName);
            }
            $fileName = $request->file('acta_archivo')->store('cm_ccs_cv_temt');
            $fileName = explode('/', $fileName)[1];
        }
        $this->repo->updateActaArchivo($id, $fileName);
        // return $fileName;
    }
}
