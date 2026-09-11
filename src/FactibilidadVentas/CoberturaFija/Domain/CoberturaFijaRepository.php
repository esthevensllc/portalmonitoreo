<?php

namespace AMovil\FactibilidadVentas\CoberturaFija\Domain;

use AMovil\Shared\Domain\Criteria\Criteria;

interface CoberturaFijaRepository
{
    public function getPolygonsFtthByTipo(string $tipo);
    public function getPolygonsHfc();
    public function findPolygonFtthById($id);
    public function findPolygonHfcById($id);
}
