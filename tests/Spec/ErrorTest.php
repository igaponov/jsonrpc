<?php

namespace JsonRpc\Spec;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerializeReturnsArray()
    {
        $code = 0;
        $message = 'err';
        $data = ['data'];
        $error = new Error($code, $message, $data);
        $serialized = $error->jsonSerialize();
        $this->assertEquals(compact('code', 'message', 'data'), $serialized);
    }
}
