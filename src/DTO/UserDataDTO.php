<?php

namespace App\DTO;

use App\ValueObject\PhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

class UserDataDTO
{
    #[Assert\NotBlank(message: 'First name cannot be blank.')]
    #[Assert\Length(
        min: 3,
        max: 35,
        minMessage: "First name must be at least {{ limit }} characters long",
        maxMessage: "First name cannot be longer than {{ limit }} characters"
    )]
    private readonly string $firstName;
    #[Assert\NotBlank(message: 'Last name name cannot be blank.')]
    #[Assert\Length(
        min: 3,
        max: 35,
        minMessage: "First name must be at least {{ limit }} characters long",
        maxMessage: "First name cannot be longer than {{ limit }} characters"
    )]
    private readonly string $lastName;
    /**
     * @var PhoneNumber[]
     */
    #[Assert\NotBlank]
    #[Assert\Type("array")]
    #[Assert\All([new Assert\Type(type: PhoneNumber::class)])]
    #[Assert\Valid]
    private readonly array $phoneNumbers;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string[] $phoneNumbers
     */
    public function __construct(
        string $firstName,
        string $lastName,
        array  $phoneNumbers
    )
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumbers = array_map(function (string $phoneNumber) {
            return new PhoneNumber($phoneNumber);
        }, $phoneNumbers);
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
        return array_map(fn(PhoneNumber $phoneNumber) => $phoneNumber->getPhoneNumber(), $this->phoneNumbers);
    }

}