<?php

namespace App\DTO;

class IpLocationDTO
{
    public function __construct(
        private string $country,
        private string $ipAddress,
    )
    {
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