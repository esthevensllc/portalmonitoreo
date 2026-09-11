<?php

namespace AMovil\Fija\OperacionFija\Services;

use AMovil\Fija\OperacionFija\Domain\Exceptions\FuenteFijaNotFound;
use AMovil\Fija\OperacionFija\Domain\FuenteFijaRepository;

class FuenteFijaDeleter
{
    private $repo;

    public function __construct(FuenteFijaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($id): void
    {
        if(!$this->repo->exists($id)){
            throw new FuenteFijaNotFound();
        }
        $this->repo->delete($id);
    }
}
