<?php

namespace AMovil\Fija\OperacionFijaPolyline\Domain;

interface FuenteFijaEnlaceRepository
{

    public function create(string $path): FuenteFijaEnlace;
    public function delete($id): void;
    public function get();
}
