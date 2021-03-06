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
        $this->assertEquals(unserialize($string), $batch);
    }
}
