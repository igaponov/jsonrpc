<?php

namespace JsonRpc\Spec;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerializeReturnsArray()
    {
        $code = 0;
        $message = 'err';
        $data = ['data'];
        $serialized = json_encode(new Error($code, $message, $data));
        $error = json_decode($serialized);
        $this->assertAttributeEquals($code, 'code', $error);
        $this->assertAttributeEquals($message, 'message', $error);
        $this->assertAttributeEquals($data, 'data', $error);
    }
}
