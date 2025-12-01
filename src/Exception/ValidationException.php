<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \RuntimeException
{
    public function __construct(private ConstraintViolationListInterface $constraintViolationList)
    {
        parent::__construct("Validation failed", 422);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}