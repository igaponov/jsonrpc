<?php

namespace JsonRpc\Spec;

class RequestBatchTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $response1 = new Request('method1', [], 2);
        $response2 = new Request('method2');
        $batch = new BatchRequest([$response1, $response2]);
        $string = json_encode($batch);
        $this->assertSame('[{"jsonRpc":"2.0","id":2,"method":"method1","params":[]},{"jsonRpc":"2.0","method":"method2"}]', $string);
    }

    public function testSerialize()
    {
        $response1 = new Request('method1', ['param' => false], 2);
        $response2 = new Request('method2');
        $batch = new BatchRequest([$response1, $response2]);
        $string = serialize($batch);
        $this->assertSame('C:25:"JsonRpc\Spec\BatchRequest":490:{x:i:0;a:2:{i:0;O:20:"JsonRpc\Spec\Request":4:{s:28:" JsonRpc\Spec\Request method";s:7:"method1";s:28:" JsonRpc\Spec\Request params";a:1:{s:5:"param";b:0;}s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";i:2;}i:1;O:20:"JsonRpc\Spec\Request":4:{s:28:" JsonRpc\Spec\Request method";s:7:"method2";s:28:" JsonRpc\Spec\Request params";N;s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";N;}};m:a:1:{s:12:" * itemClass";s:21:"\JsonRpc\Spec\Request";}}', $string);
    }

    public function testUnserialize()
    {
        /** @var Batch $batch */
        $batch = unserialize('C:25:"JsonRpc\Spec\BatchRequest":490:{x:i:0;a:2:{i:0;O:20:"JsonRpc\Spec\Request":4:{s:28:" JsonRpc\Spec\Request method";s:7:"method1";s:28:" JsonRpc\Spec\Request params";a:1:{s:5:"param";b:0;}s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";i:2;}i:1;O:20:"JsonRpc\Spec\Request":4:{s:28:" JsonRpc\Spec\Request method";s:7:"method2";s:28:" JsonRpc\Spec\Request params";N;s:26:" JsonRpc\Spec\Unit jsonRpc";s:3:"2.0";s:21:" JsonRpc\Spec\Unit id";N;}};m:a:1:{s:12:" * itemClass";s:21:"\JsonRpc\Spec\Request";}}');
        $this->assertInstanceOf('\JsonRpc\Spec\BatchRequest', $batch);
        $this->assertSame('method1', $batch->offsetGet(0)->getMethod());
        $this->assertSame(['param' => false], $batch->offsetGet(0)->getParams());
        $this->assertSame(2, $batch->offsetGet(0)->getId());
        $this->assertSame('method2', $batch->offsetGet(1)->getMethod());
    }
}
