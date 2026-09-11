<?php

namespace AMovil\Fija\OperacionFijaPolyline\Services;

use AMovil\Fija\OperacionFijaPolyline\Domain\FuenteFijaEnlaceRepository;
use AMovil\Shared\Application\Response;

class FuenteFijaEnlaceWriter
{
    private $repo;

    public function __construct(FuenteFijaEnlaceRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(string $path){
        $enlace = $this->repo->create($path);
        return new Response([], $enlace->toArray());
    }

    public function delete(int $id){
        $this->repo->delete($id);
        return new Response([]);
    }
}
