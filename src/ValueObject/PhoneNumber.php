<?php

namespace App\ValueObject;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

#[ODM\EmbeddedDocument]
class PhoneNumber
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 10, max: 255)]
    #[Assert\Regex(pattern: '/^\+380\d{9}$/', message: 'Phone number must be a valid phone number. Pattern: {{ pattern }}')]
    #[ODM\Field(type: 'string')]
    private string $phoneNumber;

    /**
     * @param string $phoneNumber
     */
    public function __construct(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): PhoneNumber
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

}