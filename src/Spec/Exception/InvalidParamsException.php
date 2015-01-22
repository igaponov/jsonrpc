<?php

namespace JsonRpc\Spec\Exception;

use Exception;

/**
 * Invalid method parameter(s).
 */
class InvalidParamsException extends \InvalidArgumentException
{
    public function __construct($message = "Invalid params", $code = -32602, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
