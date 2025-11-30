<?php

namespace App\Enum;

enum OrderBy: string
{
    case FIRST_NAME = "firstName";
    case LAST_NAME = "lastName";
    case COUNTRY = "country";
}
