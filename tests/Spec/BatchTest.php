<?php

namespace JsonRpc\Spec;

class BatchTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckElementWithProperClass()
    {
        $batch = $this->getBatchMock(null);
        $unit = $this->getUnitMock();
        $batch->append($unit);
        $this->assertSame($unit, $batch->offsetGet(0));
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
