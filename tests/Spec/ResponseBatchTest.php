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
        $response3 = new Response(null, new Error(0, 'err', []));
        $batch = new BatchResponse([$response1, $response2, $response3]);
        $string = serialize($batch);
        $this->assertEquals(unserialize($string), $batch);
    }
}
