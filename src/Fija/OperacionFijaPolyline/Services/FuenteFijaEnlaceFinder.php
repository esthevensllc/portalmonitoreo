<?php

namespace AMovil\Fija\OperacionFijaPolyline\Services;

use AMovil\Fija\OperacionFijaPolyline\Domain\FuenteFijaEnlaceRepository;
use AMovil\Shared\Application\Response;

class FuenteFijaEnlaceFinder
{
    private $repo;

    public function __construct(FuenteFijaEnlaceRepository $repo)
    {
        $this->repo = $repo;
    }

    public function get(){
        return $this->repo->get();
    }
}
