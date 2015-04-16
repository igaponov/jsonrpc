<?php

namespace Spec;

use JsonRpc\Spec\Error;
use JsonRpc\Spec\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testResultOrErrorMustBePassedToConstructor()
    {
        new Response();
    }

    public function testConstruction()
    {
        $result = 1;
        $code = 0;
        $message = 'System error';
        $data = [];
        $error = new Error($code, $message, $data);
        $id = 2;
        $response = new Response($result, $error, $id);
        $this->assertSame($result, $response->getResult());
        $this->assertSame($error, $response->getError());
        $this->assertSame($code, $response->getErrorCode());
        $this->assertSame($message, $response->getErrorMessage());
        $this->assertSame($data, $response->getErrorData());
        $this->assertSame($id, $response->getId());
    }

    public function testResponseReturnNullOnEmptyError()
    {
        $response = new Response(1);
        $this->assertNull($response->getError());
        $this->assertNull($response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertNull($response->getErrorData());
    }

    public function testResultSerialization()
    {
        $response = new Response(2);
        $serialized = json_encode($response);
        $this->assertContains('"result":2', $serialized);
        $this->assertNotContains('error', $serialized);
    }

    public function testErrorSerialization()
    {
        $response = new Response(null, new Error(0, ''));
        $serialized = json_encode($response);
        $this->assertNotContains('result', $serialized);
        $this->assertContains('"error":{', $serialized);
    }
}
