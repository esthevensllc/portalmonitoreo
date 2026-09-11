<?php

namespace AMovil\Maps\AfectacionEnergia\Domain;

use AMovil\Shared\Domain\Criteria\Criteria;

interface AfectacionEnergiaRepository
{
    public function getCantSitiosCaidos();
    public function findDetalleEnergiaByUbigeo($ubigeo);
    public function getRegionFromSites();
    public function getProvinciaFromSites();
    public function getDistritoFromSites();
    public function getGruposAfectacionFromSites();
    public function getGruposSitioFromSites();
    public function getGruposAutonomiaFromSites();
    public function getCaidosUltMesFromSites();
    public function getCaidosUlt4MesFromSites();
    public function getSites(Criteria $criteria);
}
