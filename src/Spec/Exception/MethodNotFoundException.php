<?php

namespace JsonRpc\Spec\Exception;

use Exception;

/**
 * The method does not exist / is not available.
 */
class MethodNotFoundException extends \BadMethodCallException
{
    public function __construct($message = "Method not found", $code = -32601, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
