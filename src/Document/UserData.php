<?php

namespace App\Document;

use App\Repository\UserDataRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'userData', repositoryClass: UserDataRepository::class)]
class UserData
{
    #[ODM\Id]
    private string $id;
    #[ODM\Field(type: 'string')]
    private string $firstName;
    #[ODM\Field(type: 'string')]
    private string $lastName;
    #[ODM\Field(type: 'collection')]
    private array $phoneNumbers = [];
    #[ODM\Field(type: 'string')]
    private string $ipAddress;
    #[ODM\Field(type: 'string')]
    private string $country;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): UserData
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): UserData
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): UserData
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    public function setPhoneNumbers(array $phoneNumbers): UserData
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): UserData
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): UserData
    {
        $this->country = $country;
        return $this;
    }


}