<?php

namespace JsonRpc\Spec;

class ResponseBatchTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $response1 = new Response(true);
        $response2 = new Response(2);
        $batch = new BatchResponse([$response1, $response2]);
        $string = json_encode($batch);
        $this->assertSame('[{"jsonRpc":"2.0","result":true},{"jsonRpc":"2.0","result":2}]', $string);
    }

    public function testSerialize()
    {
        $response1 = new Response(true);
        $response2 = new Response(2);
        $batch = new BatchResponse([$response1, $response2]);
        $string = serialize($batch);
        $this->assertSame('C:26:"JsonRpc\Spec\BatchResponse":453:{x:i:0;a:2:{i:0;O:21:"JsonRpc\Spec\Response":4:{s:29:" JsonRpc\Spec\Response result";b:1;s:28:" JsonRpc\Spec\Response error";N;s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";N;}i:1;O:21:"JsonRpc\Spec\Response":4:{s:29:" JsonRpc\Spec\Response result";i:2;s:28:" JsonRpc\Spec\Response error";N;s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";N;}};m:a:1:{s:12:" * itemClass";s:22:"\JsonRpc\Spec\Response";}}', $string);
    }

    public function testUnserialize()
    {
        /** @var Batch $batch */
        $batch = unserialize('C:26:"JsonRpc\Spec\BatchResponse":453:{x:i:0;a:2:{i:0;O:21:"JsonRpc\Spec\Response":4:{s:29:" JsonRpc\Spec\Response result";b:1;s:28:" JsonRpc\Spec\Response error";N;s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";N;}i:1;O:21:"JsonRpc\Spec\Response":4:{s:29:" JsonRpc\Spec\Response result";i:2;s:28:" JsonRpc\Spec\Response error";N;s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";N;}};m:a:1:{s:12:" * itemClass";s:22:"\JsonRpc\Spec\Response";}}');
        $this->assertInstanceOf('\JsonRpc\Spec\BatchResponse', $batch);
        $this->assertSame(true, $batch->offsetGet(0)->getResult());
        $this->assertSame(2, $batch->offsetGet(1)->getResult());
    }
}
