<?php

namespace App\Modules\Shared\Services;

class Response
{
    private $errors = [];
    private $data = [];

    public function __construct($errors, $data = null)
    {
        $this->errors = $errors;
        $this->data = $data;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function getData(){
        return $this->data;
    }

    public function passes(){
        return count($this->errors) == 0 ? true : false;
    }

    public function fails(){
        return !$this->passes();
    }

    public function toArray(): array {
        return [
            'passes' => $this->passes(),
            'errors' => $this->getErrors(),
            'data' => $this->getData(),
        ];
    }
}
