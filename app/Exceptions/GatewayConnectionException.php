<?php

namespace App\Exceptions;

use Exception;

class GatewayConnectionException extends Exception
{
    public function __construct(private readonly string $connectionError)
    {
        parent::__construct();
    }


    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }

    public function getConnectionError(): string
    {
        return $this->connectionError;
    }

}
