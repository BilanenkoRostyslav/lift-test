<?php

namespace App\Exception;

use Throwable;

class ApiIpLocateException extends \RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Error while getting ip address", $previous);
    }
}