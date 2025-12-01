<?php

namespace App\DTO;

class UserDataResponseDTO
{
    /**
     * @param string[] $phoneNumbers
     */
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly array  $phoneNumbers,
        private readonly string $ipAddress,
        private readonly string $country,
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

    /**
     * @return string[]
     */
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