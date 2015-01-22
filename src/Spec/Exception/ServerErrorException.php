<?php

namespace JsonRpc\Spec\Exception;

use Exception;

/**
 * Reserved for implementation-defined server-errors.
 */
class ServerErrorException extends \Exception
{
    public function __construct($message = "Server error", $code = -32000, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
