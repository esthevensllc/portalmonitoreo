<?php

namespace AMovil\FactibilidadVentas\CoberturaFija\Services;

use AMovil\FactibilidadVentas\CoberturaFija\Domain\CoberturaFijaRepository;
use AMovil\Shared\Application\Response;
use AMovil\Shared\Domain\Criteria\Criteria;
use AMovil\Shared\Domain\Criteria\Filters;
use AMovil\Shared\Domain\Criteria\Order;
use Illuminate\Support\Facades\Cache;

class CoberturaFijaFinder
{
    private $repo;

    public function __construct(CoberturaFijaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getPolygonsFtthByTipo(string $tipo)
    {
        $cacheKey = "cobfijaventas_ftth_".$tipo;
        if(Cache::has($cacheKey)){
            return Cache::get($cacheKey);
        }
        $data = $this->repo->getPolygonsFtthByTipo($tipo);
        Cache::put($cacheKey, $data);
        return $data;
    }

    public function findPolygonFtth($id): Response {
        $data = $this->repo->findPolygonFtthById($id);
        if($data === null){
            return new Response(["message" => "El polygono no existe"]);
        }
        return new Response([], $data);
    }

    public function getPolygonsHfc()
    {
        $cacheKey = "cobfijaventas_hfc";
        if(Cache::has($cacheKey)){
            return Cache::get($cacheKey);
        }
        $data = $this->repo->getPolygonsHfc();
        Cache::put($cacheKey, $data);
        return $data;
    }

    public function findPolygonHfc($id): Response {
        $data = $this->repo->findPolygonHfcById($id);
        if($data === null){
            return new Response(["message" => "El polygono no existe"]);
        }
        return new Response([], $data);
    }
}
