<?php

namespace JsonRpc\Spec;

class BatchRequest extends Batch
{
    public function __construct($input = [], $flags = 0, $iteratorClass = "ArrayIterator")
    {
        $this->itemClass = '\JsonRpc\Spec\Request';
        parent::__construct($input, $flags, $iteratorClass);
    }
}
