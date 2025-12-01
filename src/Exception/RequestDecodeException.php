<?php

namespace App\Exception;

use Throwable;

class RequestDecodeException extends \Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Request decode error", 0, $previous);
    }
}