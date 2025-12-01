<?php

namespace App\DTO;

use App\Enum\OrderBy;
use App\Enum\OrderDirection;
use Symfony\Component\Validator\Constraints as Assert;

class GetUserDataDTO
{

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(
            callback: [OrderBy::class, 'values'],
            message: 'The value "{{ value }}" is invalid. Allowed values are: {{ choices }}'
        )]
        private readonly string $orderBy,

        #[Assert\NotBlank]
        #[Assert\Choice(
            callback: [OrderDirection::class, 'values'],
            message: 'The value "{{ value }}" is invalid. Allowed values are: {{ choices }}'
        )]
        private readonly string $orderDirection,
    )
    {
    }

    public function getOrderBy(): OrderBy
    {
        return OrderBy::from($this->orderBy);
    }

    public function getOrderDirection(): OrderDirection
    {
        return OrderDirection::from($this->orderDirection);
    }
}