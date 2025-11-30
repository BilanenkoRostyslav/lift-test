<?php

namespace App\DTO;

use App\Enum\OrderBy;
use App\Enum\OrderDirection;

class GetUserDataDTO
{
    private OrderBy $orderBy;
    private OrderDirection $orderDirection;

    public function __construct(
        string $orderBy,
        string $orderDirection,
    )
    {
        $this->orderBy = OrderBy::from($orderBy);
        $this->orderDirection = OrderDirection::from($orderDirection);
    }

    public function getOrderBy(): OrderBy
    {
        return $this->orderBy;
    }

    public function getOrderDirection(): OrderDirection
    {
        return $this->orderDirection;
    }
}