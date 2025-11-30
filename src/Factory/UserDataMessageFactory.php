<?php

namespace App\Factory;

use App\DTO\UserDataDTO;
use App\DTO\IpLocationDTO;
use App\Message\UserDataInsertMessage;

class UserDataMessageFactory
{
    public function create(UserDataDTO $userDTO, IpLocationDTO $ipLocationDTO): UserDataInsertMessage
    {
        return new UserDataInsertMessage(
            $userDTO->getFirstName(),
            $userDTO->getLastName(),
            $userDTO->getPhoneNumbers(),
            $ipLocationDTO->getCountry(),
            $ipLocationDTO->getIpAddress(),
        );
    }
}