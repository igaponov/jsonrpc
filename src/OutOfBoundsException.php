<?php

namespace JsonRpc;

use Exception;
use OutOfBoundsException as BaseOutOfBoundsException;

class OutOfBoundsException extends BaseOutOfBoundsException
{
    public function __construct($index, $code = 0, Exception $previous = null)
    {
        parent::__construct("Index $index is out of bounds.", $code, $previous);
    }
}
