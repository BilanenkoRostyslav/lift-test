<?php

namespace App\Factory;

use App\Document\UserData;

class UserDataDocumentFactory
{
    public function create(string $firstName, string $lastName, array $phoneNumbers, string $ipAddress, string $country): UserData
    {
        $userData = new UserData();
        $userData->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPhoneNumbers($phoneNumbers)
            ->setIpAddress($ipAddress)
            ->setCountry($country);

        return $userData;
    }
}