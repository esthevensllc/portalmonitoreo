<?php

namespace App\Modules\MapaPoligonos\Services;

use App\Modules\MapaPoligonos\Entity\CreatePoligonoValidator;
use App\Modules\MapaPoligonos\Repository\MapaPoligonoRepository;
use App\Modules\Shared\Services\Response;

class CreateMapaPoligono
{
    private $repo;
    private $validator;
    public function __construct(MapaPoligonoRepository $repo)
    {
        $this->repo = $repo;
        $this->validator = new CreatePoligonoValidator();
    }

    public function __invoke($data): Response
    {
        $this->validator->validate($data);
        if($this->validator->passes()){
            $this->repo->create($data);
            return new Response([]);
        }
        return new Response($this->validator->errors());
    }
}
