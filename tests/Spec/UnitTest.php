<?php

namespace JsonRpc\Spec;

class UnitTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $unit = $this->getUnitMock(null);
        $this->assertNull($unit->getId());
    }

    public function testConstructWithId()
    {
        $unit = $this->getUnitMock(null, [5]);
        $this->assertSame(5, $unit->getId());
    }

    public function testJsonRpcVersion()
    {
        $unit = $this->getUnitMock(null);
        $this->assertSame('2.0', $unit->getJsonRpc());
    }

    /**
     * @param array $methods
     * @param array $arguments
     * @param bool $callConstructor
     * @return \JsonRpc\Spec\Unit|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUnitMock($methods = [], $arguments = [], $callConstructor = true)
    {
        return $this->getMock('\JsonRpc\Spec\Unit', $methods, $arguments, '', $callConstructor);
    }
}
