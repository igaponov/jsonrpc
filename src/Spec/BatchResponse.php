<?php

namespace JsonRpc\Spec;

class BatchResponse extends Batch
{
    public function __construct($input = [], $flags = 0, $iteratorClass = "ArrayIterator")
    {
        $this->itemClass = '\JsonRpc\Spec\Response';
        parent::__construct($input, $flags, $iteratorClass);
    }
}
