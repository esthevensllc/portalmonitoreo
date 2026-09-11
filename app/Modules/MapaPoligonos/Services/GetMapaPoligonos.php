<?php

namespace App\Modules\MapaPoligonos\Services;

use App\Modules\MapaPoligonos\Repository\MapaPoligonoRepository;
use App\Modules\Shared\Services\Response;

class GetMapaPoligonos
{
    private $repo;
    
    public function __construct(MapaPoligonoRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(): Response
    {
        return new Response([], $this->repo->getAsPoligonFormat());
    }
}
