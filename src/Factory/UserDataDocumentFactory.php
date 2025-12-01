<?php

namespace App\Factory;

use App\Document\UserData;
use App\ValueObject\PhoneNumber;

class UserDataDocumentFactory
{
    /**
     * @param string[] $phoneNumbers
     */
    public function create(string $firstName, string $lastName, array $phoneNumbers, string $ipAddress, string $country): UserData
    {
        $userData = new UserData();
        $phoneNumbers = array_map(function (string $phoneNumber) {
            return new PhoneNumber($phoneNumber);
        }, $phoneNumbers);
        $userData->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPhoneNumbers($phoneNumbers)
            ->setIpAddress($ipAddress)
            ->setCountry($country);

        return $userData;
    }
}