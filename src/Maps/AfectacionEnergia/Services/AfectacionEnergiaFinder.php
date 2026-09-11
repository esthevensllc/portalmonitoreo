<?php

namespace AMovil\Maps\AfectacionEnergia\Services;

use AMovil\Maps\AfectacionEnergia\Domain\AfectacionEnergiaRepository;
use AMovil\Shared\Domain\Criteria\Criteria;

class AfectacionEnergiaFinder
{
    private $repository;

    public function __construct(AfectacionEnergiaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCantSitiosCaidos(){
        return $this->repository->getCantSitiosCaidos();
    }

    public function findDetalleEnergiaByUbigeo($ubigeo){
        return $this->repository->findDetalleEnergiaByUbigeo($ubigeo);
    }

    public function getRegionFromSites(){
        return $this->repository->getRegionFromSites();
    }

    public function getProvinciaFromSites(){
        return $this->repository->getProvinciaFromSites();
    }

    public function getDistritoFromSites(){
        return $this->repository->getDistritoFromSites();
    }

    public function getGruposAfectacionFromSites(){
        return $this->repository->getGruposAfectacionFromSites();
    }

    public function getGruposSitioFromSites(){
        return $this->repository->getGruposSitioFromSites();
    }

    public function getGruposAutonomiaFromSites(){
        return $this->repository->getGruposAutonomiaFromSites();
    }

    public function getCaidosUltMesFromSites(){
        return $this->repository->getCaidosUltMesFromSites();
    }

    public function getCaidosUlt4MesFromSites(){
        return $this->repository->getCaidosUlt4MesFromSites();
    }

    public function getSites(Criteria $criteria){
        return $this->repository->getSites($criteria);
    }

}
