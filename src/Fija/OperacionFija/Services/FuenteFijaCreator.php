<?php

namespace AMovil\Fija\OperacionFija\Services;

use AMovil\Fija\OperacionFija\Domain\FuenteFija;
use AMovil\Fija\OperacionFija\Domain\FuenteFijaRepository;
use AMovil\Fija\OperacionFija\Domain\Services\FuenteFijaCreateValidator;
use AMovil\Shared\Application\Response;
use DateTime;

class FuenteFijaCreator
{
    private $repo;
    private $validator;

    public function __construct(FuenteFijaRepository $repo)
    {
        $this->repo = $repo;
        $this->validator = new FuenteFijaCreateValidator();
    }

    public function __invoke(array $data): Response
    {
        $validation = $this->validator->validate($data);
        if(!$validation->fails()){
            $fuente = new FuenteFija(
                null,
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
            $fuenteFija = $this->repo->create($fuente);
            return new Response([], $fuenteFija->toArray());
        }
        return new Response($validation->errors()->toArray());
    }
}
