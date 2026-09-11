<?php

namespace AMovil\Fija\OperacionFija\Services;

use AMovil\Fija\OperacionFija\Domain\Exceptions\FuenteFijaNotFound;
use AMovil\Fija\OperacionFija\Domain\FuenteFija;
use AMovil\Fija\OperacionFija\Domain\FuenteFijaRepository;

class FuenteFijaFinder
{
    private $repo;

    public function __construct(FuenteFijaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function findById($id): FuenteFija {
        $fuente = $this->repo->findById($id);
        if($fuente === null){
            throw new FuenteFijaNotFound();
        }
        return $fuente;
    }

    public function getNumTipoRespaldoByRegion(){
        return $this->repo->getNumTipoRespaldoByRegion();
    }

    public function getNumTipoRespaldoByMonth(){
        return $this->repo->getNumTipoRespaldoByMonth();
    }
}
