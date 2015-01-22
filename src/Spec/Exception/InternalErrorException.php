<?php

namespace JsonRpc\Spec\Exception;

use Exception;

/**
 * Internal JSON-RPC error.
 */
class InternalErrorException extends \Exception
{
    public function __construct($message = "Internal error", $code = -32603, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
