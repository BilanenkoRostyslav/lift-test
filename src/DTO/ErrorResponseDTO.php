<?php

namespace App\DTO;


class ErrorResponseDTO
{
    public function __construct(private string $message, private mixed $errors = null)
    {
    }


    public function getMessage(): string
    {
        return $this->message;
    }


    public function getErrors(): mixed
    {
        return $this->errors;
    }

}