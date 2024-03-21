<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GatewayConnectionException extends Exception
{
    public function __construct(private string $connectionError)
    {
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
