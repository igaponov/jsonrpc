<?php

namespace JsonRpc\Spec\Exception;

use Exception;

/**
 * Invalid JSON was received by the server.
 * An error occurred on the server while parsing the JSON text.
 */
class ParseErrorException extends \RuntimeException
{
    public function __construct($message = "Parse error", $code = -32700, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
