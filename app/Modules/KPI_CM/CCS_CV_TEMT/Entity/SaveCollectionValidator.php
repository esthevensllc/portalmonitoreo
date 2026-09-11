<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Entity;

use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMT_Repository;
use App\Modules\Shared\Entity\Validator;

class SaveCollectionValidator extends Validator
{
    private $createVal;
    private $updateVal;
    private $repo;
    
    public function __construct(CCS_CV_TEMT_Repository $repo)
    {
        $this->createVal = new CreateValidator();
        $this->updateVal = new UpdateValidator();
        $this->repo = $repo;
    }

    public function validate($collection){
        $errors = [];
        foreach ($collection as $key => $values) {
            $id = $this->mapId($values);
            if($id === null){
                $this->createVal->validate($values);
                if($this->createVal->fails()){
                    $errors['row.'.$key] = $this->createVal->errors();
                }
            }else{
                $this->updateVal->validate($values);
                if($this->updateVal->fails()){
                    $errors['row.'.$key] = $this->updateVal->errors();
                }
            }
            // if($this->repo->find($id) === null){
            //     $this->createVal->validate($values);
            //     if($this->createVal->fails()){
            //         $errors['row.'.$key] = $this->createVal->errors();
            //     }
            // }else{
            //     $this->updateVal->validate($values);
            //     if($this->updateVal->fails()){
            //         $errors['row.'.$key] = $this->updateVal->errors();
            //     }
            // }
        }
        $this->setErrors($errors);
    }

    private function mapId($values){
        return isset($values['id']) ? $values['id'] : null;
    }
}
