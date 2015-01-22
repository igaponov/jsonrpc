<?php

namespace JsonRpc\Spec\Exception;

use Exception;

/**
 * The JSON sent is not a valid Request object.
 */
class InvalidRequestException extends \RuntimeException
{
    public function __construct($message = "Invalid request", $code = -32600, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
