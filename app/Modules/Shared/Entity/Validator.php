<?php

namespace App\Modules\Shared\Entity;

class Validator
{
    private $errors = [];

    function errors(): array {
        return $this->errors;
    }

    function collectionErrorsImploded(): array {
        $array = $this->errors();
        foreach($this->errors() as $index => $errorsOfRow){
            foreach($errorsOfRow as $field => $errorsOfField){
                $array[$index][$field] = implode(",", $errorsOfField);
            }
        }
        return $array;
    }

    function addErrors($errors): void {
        foreach ($errors as $key => $err){
            $this->errors[$key] = $err;
        }
    }

    function setErrors($errors): void {
        $this->errors = $errors;
    }

    function passes(): bool {
        return count($this->errors) == 0 ? true : false;
    }

    function fails(): bool {
        return !$this->passes();
    }
}
