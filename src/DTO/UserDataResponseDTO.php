<?php

namespace App\DTO;

class UserDataResponseDTO
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private array  $phoneNumbers,
        private string $ipAddress,
        private string $country,
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

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
    
}