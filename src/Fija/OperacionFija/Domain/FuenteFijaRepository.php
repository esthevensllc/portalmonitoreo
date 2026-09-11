<?php

namespace AMovil\Fija\OperacionFija\Domain;

interface FuenteFijaRepository
{
    public function findById($id): ?FuenteFija;
    public function exists($id): bool;
    public function create(FuenteFija $fuente): FuenteFija;
    public function update(FuenteFija $fuente): void;
    public function updateImages(FuenteFija $fuente): void;
    public function delete($id): void;
    public function get();
    public function getNumTipoRespaldoByRegion();
    public function getNumTipoRespaldoByMonth();
}
