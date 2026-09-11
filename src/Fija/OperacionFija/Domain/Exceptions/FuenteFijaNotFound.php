<?php

namespace AMovil\Fija\OperacionFija\Domain\Exceptions;

use Exception;

class FuenteFijaNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct("La fuente fija no existe");
    }
}
