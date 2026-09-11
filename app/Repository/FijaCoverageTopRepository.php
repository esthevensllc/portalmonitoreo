<?php

namespace App\Repository;

class FijaCoverageTopRepository
{
    private $queryByDist = [
        "4" => [
        ]
    ];

    public function getConfigBy(int $distribucionId, int $kpiId)
    {
        if(array_key_exists($distribucionId, $this->queryByDist)){
            if(array_key_exists($kpiId, $this->queryByDist[$distribucionId])){
                return $this->queryByDist[$distribucionId][$kpiId];
            }
        }
        return null;
    }
}
