<?php

namespace JsonRpc\Utils;

class DecoderTest extends \PHPUnit_Framework_TestCase
{
    public function testDecodeRequest()
    {
        $json = '{"jsonRpc":"2.0","id":2,"method":"method1","params":{"param":"val"}}';
        $request = Decoder::decode($json);
        $this->assertInstanceOf('\JsonRpc\Spec\Request', $request);
        $this->assertSame('method1', $request->getMethod());
        $this->assertSame(2, $request->getId());
        $this->assertSame(['param' => 'val'], $request->getParams());
    }

    public function testDecodeResponse()
    {
        $json = '{"jsonRpc":"2.0","result":true,"id":7}';
        $response = Decoder::decode($json);
        $this->assertInstanceOf('\JsonRpc\Spec\Response', $response);
        $this->assertSame(7, $response->getId());
        $this->assertSame(true, $response->getResult());
        $this->assertNull($response->getError());
    }

    public function testDecodeResponseWithError()
    {
        $json = '{"jsonRpc":"2.0","error":{"code":237,"message":"Fatal error","data":[]},"id":3}';
        $response = Decoder::decode($json);
        $this->assertInstanceOf('\JsonRpc\Spec\Response', $response);
        $this->assertSame(3, $response->getId());
        $this->assertNull($response->getResult());
        $this->assertInstanceOf('\JsonRpc\Spec\Error', $response->getError());
        $this->assertSame(237, $response->getError()->getCode());
        $this->assertSame('Fatal error', $response->getError()->getMessage());
        $this->assertSame([], $response->getError()->getData());
    }

    public function testDecodeRequestBatch()
    {
        $json = '[{"jsonRpc":"2.0","method":"method1","params":[]},{"jsonRpc":"2.0","method":"method2","id":2}]';
        $requestBatch = Decoder::decode($json);
        $this->assertInstanceOf('\JsonRpc\Spec\BatchRequest', $requestBatch);
        $this->assertInstanceOf('\JsonRpc\Spec\Request', $requestBatch->offsetGet(0));
        $this->assertInstanceOf('\JsonRpc\Spec\Request', $requestBatch->offsetGet(1));
    }

    public function testDecodeResponseBatch()
    {
        $json = '[{"jsonRpc":"2.0","result":true},{"jsonRpc":"2.0","error":{"code":237,"message":"Fatal error","data":[]}}]';
        $requestBatch = Decoder::decode($json);
        $this->assertInstanceOf('\JsonRpc\Spec\BatchResponse', $requestBatch);
        $this->assertInstanceOf('\JsonRpc\Spec\Response', $requestBatch->offsetGet(0));
        $this->assertInstanceOf('\JsonRpc\Spec\Response', $requestBatch->offsetGet(1));
        $this->assertInstanceOf('\JsonRpc\Spec\Error', $requestBatch->offsetGet(1)->getError());
    }
}
