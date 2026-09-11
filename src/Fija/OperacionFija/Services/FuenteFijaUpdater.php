<?php

namespace AMovil\Fija\OperacionFija\Services;

use AMovil\Fija\OperacionFija\Domain\FuenteFija;
use AMovil\Fija\OperacionFija\Domain\FuenteFijaRepository;
use AMovil\Fija\OperacionFija\Domain\Services\FuenteFijaUpdateValidator;
use AMovil\Shared\Application\Response;
use DateTime;

class FuenteFijaUpdater
{
    private $repo;
    private $validator;

    public function __construct(FuenteFijaRepository $repo)
    {
        $this->repo = $repo;
        $this->validator = new FuenteFijaUpdateValidator();
    }

    public function __invoke(array $data): Response
    {
        $validation = $this->validator->validate($data);
        if(!$validation->fails()){
            $fuente = new FuenteFija(
                $data["id"],
                $data["plano"],
                $data["tipo_nodo"],
                $data["tipo_fuente"],
                $data["ubicado_en"],
                $data["tiene_baterias"],
                $data["anio_fab"],
                $data["tipo_respaldo"],
                $data["amp"],
                $data["candado"],
                $data["barra"],
                $data["seguro_h"],
                $data["seguro_baterias"],
                $data["fecha_manto"] !== null ? DateTime::createFromFormat("Y-m-d", $data["fecha_manto"]) : null,
                $data["anio_manto"],
                $data["mes_manto"],
                $data["referido"],
                $data["region"],
                $data["distrito"],
                $data["segmento_urbano"],
                $data["gestion_campo"],
                $data["nivel_de_seguridad"],
                $data["comentario"],
                $data["latitud"],
                $data["longitud"],
            );
            $this->repo->update($fuente);
            return new Response([]);
        }
        return new Response($validation->errors()->toArray());
    }

    public function updateImages($id, array $files, $mode){
        $fuente = $this->repo->findById($id);
        if($mode === "update"){
            $imagesToDelete = $fuente->getImages();
            foreach($imagesToDelete as $file){
                unlink(public_path($file));
            }
        }
        $filesSaved = [];
        foreach($files as $file){
            $filename = uniqid()."_".$file->getClientOriginalName();
            $file->move(public_path("operacion_fija"), $filename);
            $filesSaved[] = "operacion_fija/{$filename}";
        }

        if($mode === "update"){
            $fuente->setImages($filesSaved);
        }else{
            foreach($filesSaved as $file){
                $fuente->addImage($file);
            }
        }

        $this->repo->updateImages($fuente);
    }
}
