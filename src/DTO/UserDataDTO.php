<?php

namespace App\DTO;

class UserDataDTO
{
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        /**
         * TODO: Create VO
         * TODO: Add Validation
         */
        private readonly array  $phoneNumbers,

    )
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

}