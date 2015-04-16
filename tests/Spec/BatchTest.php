<?php

namespace JsonRpc\Spec;

class BatchTest extends \PHPUnit_Framework_TestCase
{
    public function testAppendWithValidElement()
    {
        $batch = $this->getBatchMock(null);
        $unit = $this->getUnitMock();
        $batch->append($unit);
        $this->assertSame($unit, $batch->offsetGet(0));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAppendWithInvalidElement()
    {
        $batch = $this->getBatchMock(null);
        $batch->append(1);
    }

    public function testExchangeArrayWithValidElement()
    {
        $batch = $this->getBatchMock(null);
        $unit = $this->getUnitMock();
        $batch->exchangeArray([$unit]);
        $this->assertSame($unit, $batch->offsetGet(0));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExchangeArrayWithInvalidElement()
    {
        $batch = $this->getBatchMock(null);
        $batch->append([1]);
    }

    public function testConstructWithValidElement()
    {
        $unit = $this->getUnitMock();
        $batch = $this->getBatchMock(null, [[$unit]]);
        $this->assertSame($unit, $batch->offsetGet(0));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructWithInvalidElement()
    {
        $this->getBatchMock(null, [[1]]);
    }

    /**
     * @param array $methods
     * @param array $arguments
     * @param bool $callConstructor
     * @return Batch|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBatchMock($methods = [], $arguments = [], $callConstructor = true)
    {
        return $this->getMock('\JsonRpc\Spec\Batch', $methods, $arguments, '', $callConstructor);
    }

    /**
     * @return Unit|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUnitMock()
    {
        return $this->getMock('\JsonRpc\Spec\Unit');
    }
}
