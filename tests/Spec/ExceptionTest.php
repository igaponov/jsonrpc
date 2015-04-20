<?php

namespace Spec;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider exceptionProvider()
     *
     * @param string $exception
     * @param string $message
     * @param int $code
     */
    public function testInternalErrorException($exception, $message, $code)
    {
        $this->setExpectedException($exception, $message, $code);
        throw new $exception;
    }

    public function exceptionProvider()
    {
        return [
            ['\JsonRpc\Spec\Exception\ParseErrorException', 'Parse error', -32700],
            ['\JsonRpc\Spec\Exception\InvalidRequestException', 'Invalid request', -32600],
            ['\JsonRpc\Spec\Exception\MethodNotFoundException', 'Method not found', -32601],
            ['\JsonRpc\Spec\Exception\InvalidParamsException', 'Invalid params', -32602],
            ['\JsonRpc\Spec\Exception\InternalErrorException', 'Internal error', -32603],
            ['\JsonRpc\Spec\Exception\ServerErrorException', 'Server error', -32000],
        ];
    }
}
