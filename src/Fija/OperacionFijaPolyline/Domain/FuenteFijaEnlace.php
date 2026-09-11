<?php

namespace AMovil\Fija\OperacionFijaPolyline\Domain;

class FuenteFijaEnlace
{
    private int $id;
    private string $path;

    public function __construct($id, $path)
    {
        $this->id = $id;
        $this->path = $path;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function toArray(){
        return [
            "id" => $this->getId(),
            "path" => $this->getPath(),
        ];
    }
}
