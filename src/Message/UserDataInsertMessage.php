<?php

namespace App\Message;

use App\DTO\UserDataDTO;
use App\DTO\IpLocationDTO;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
class UserDataInsertMessage
{
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly array  $phoneNumbers,
        private readonly string $country,
        private readonly string $ipAddress,

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

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

}