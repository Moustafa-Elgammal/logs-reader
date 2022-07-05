<?php

namespace App\Parser\Services;

class Service
{
    protected $errors = [];

    public function addError($errorMessage){
        $this->errors [] = $errorMessage;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
