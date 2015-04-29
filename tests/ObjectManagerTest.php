<?php

namespace JsonRpc;

use JsonRpc\Spec\BatchResponse;
use JsonRpc\Spec\Error;
use JsonRpc\Spec\Response;

class ObjectManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRequestWithId()
    {
        $manager = $this->getManagerMock(['addRequestInternal']);
        $manager->expects($this->once())->method('addRequestInternal')->with('r1', [], 'uniqid');
        $manager->addRequest('r1', [], 'uniqid');
    }

    public function testAddRequestWithoutId()
    {
        $manager = $this->getManagerMock(['addRequestInternal']);
        $manager->expects($this->once())->method('addRequestInternal')->with('r1', [], $this->logicalNot($this->isNull()));
        $manager->addRequest('r1', []);
    }

    public function testAddNotification()
    {
        $manager = $this->getManagerMock(['addRequestInternal']);
        $manager->expects($this->once())->method('addRequestInternal')->with('r1', [], null);
        $manager->addNotification('r1', []);
    }

    public function testCommitRequest()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $transport->expects($this->once())->method('send')->with($this->isInstanceOf('\JsonRpc\Spec\Request'));
        $manager->addRequest('r1', []);
        $manager->commit();
    }

    public function testCommitNotification()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $transport->expects($this->once())->method('send')->with($this->isInstanceOf('\JsonRpc\Spec\Request'));
        $manager->addNotification('r1', []);
        $manager->commit();
    }

    public function testCommitBatchRequest()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $transport->expects($this->once())->method('send')->with($this->isInstanceOf('\JsonRpc\Spec\BatchRequest'));
        $manager->addRequest('r1', []);
        $manager->addRequest('r2', null);
        $manager->commit();
    }

    public function testCommitMixedBatchRequest()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $transport->expects($this->once())->method('send')->with($this->isInstanceOf('\JsonRpc\Spec\BatchRequest'));
        $manager->addRequest('r1', []);
        $manager->addNotification('r2', null);
        $manager->commit();
    }

    public function testGetResponse()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $response = $this->getResponseMock(['getId', 'getResult']);
        $response->expects($this->once())->method('getId')->willReturn(1);
        $response->expects($this->once())->method('getResult')->willReturn([2,5,7]);
        $transport->expects($this->once())->method('send')->willReturn($response);
        $id = $manager->addRequest('r1', [], 1);
        $manager->commit();
        $this->assertSame([2,5,7], $manager->getResult($id));
    }

    public function testGetBatchResponse()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $response1 = $this->getResponseMock(['getId', 'getResult']);
        $response2 = $this->getResponseMock(['getId', 'getResult']);
        $response1->expects($this->once())->method('getId')->willReturn(1);
        $response2->expects($this->once())->method('getId')->willReturn(2);
        $response1->expects($this->once())->method('getResult')->willReturn([2,5,7]);
        $transport->expects($this->once())->method('send')->willReturn(new BatchResponse([$response1, $response2]));
        $id1 = $manager->addRequest('r1', [], 1);
        $id2 = $manager->addRequest('r2', null, 2);
        $manager->commit();
        $this->assertSame([2,5,7], $manager->getResult($id1));
        $this->assertNull($manager->getResult($id2));
    }

    public function testGetError()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $response = $this->getResponseMock(['getId', 'getError']);
        $error = new Error(500, 'Fatal error', []);
        $response->expects($this->once())->method('getId')->willReturn(1);
        $response->expects($this->any())->method('getError')->willReturn($error);
        $transport->expects($this->once())->method('send')->willReturn($response);
        $id = $manager->addRequest('r1', [], 1);
        $manager->commit();
        $this->assertTrue($manager->hasError($id));
        $this->assertSame(500, $manager->getErrorCode($id));
        $this->assertSame('Fatal error', $manager->getError($id));
        $this->assertSame([], $manager->getErrorData($id));
    }

    public function testGetBatchWithError()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $error = new Error(500, 'Fatal error', []);
        $response1 = $this->getResponseMock(['getId', 'getResult']);
        $response2 = $this->getResponseMock(['getId', 'getError']);
        $response1->expects($this->once())->method('getId')->willReturn(1);
        $response2->expects($this->once())->method('getId')->willReturn(2);
        $response1->expects($this->once())->method('getResult')->willReturn([2,5,7]);
        $response2->expects($this->any())->method('getError')->willReturn($error);
        $transport->expects($this->once())->method('send')->willReturn(new BatchResponse([$response1, $response2]));
        $id1 = $manager->addRequest('r1', [], 1);
        $id2 = $manager->addRequest('r2', null, 2);
        $manager->commit();
        $this->assertSame([2,5,7], $manager->getResult($id1));
        $this->assertFalse($manager->hasError($id1));
        $this->assertTrue($manager->hasError($id2));
        $this->assertSame(500, $manager->getErrorCode($id2));
        $this->assertSame('Fatal error', $manager->getError($id2));
        $this->assertSame([], $manager->getErrorData($id2));
    }

    public function testGetBatchWithOneError()
    {
        $transport = $this->getTransportMock(['send']);
        $manager = $this->getManagerMock(null, $transport);
        $error = new Error(500, 'Fatal error', []);
        $response = new Response(null, $error);
        $transport->expects($this->once())->method('send')->willReturn($response);
        $id1 = $manager->addRequest('r1', [], 1);
        $id2 = $manager->addRequest('r2', null, 2);
        $manager->commit();
        $this->assertTrue($manager->hasError($id1));
        $this->assertTrue($manager->hasError($id2));
        $this->assertSame('Fatal error', $manager->getError($id1));
        $this->assertSame('Fatal error', $manager->getError($id2));
    }

    public function testRemoveRequestMethodDeletesRequest()
    {
        $transport = $this->getTransportMock(['send']);
        $transport->expects($this->never())->method('send');

        $manager = new ObjectManager($transport);
        $key = $manager->addRequest('req1', []);
        $manager->removeRequest($key);
        $manager->commit();
    }

    public function testCommitNotSendEmptyData()
    {
        $transport = $this->getTransportMock(['send']);
        $transport->expects($this->never())->method('send');

        $manager = new ObjectManager($transport);
        $manager->commit();
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testThrowsExceptionOnEmptyResponse()
    {
        $manager = $this->getManagerMock(null);
        $manager->getResult(1);
    }

    /**
     * @param null $methods
     * @param null $transport
     * @return \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    private function getManagerMock($methods = null, $transport = null)
    {
        if ($transport === null) {
            $transport = $this->getTransportMock();
        }
        return $this->getMock('\JsonRpc\ObjectManager', $methods, [$transport]);
    }

    /**
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject|TransportInterface
     */
    private function getTransportMock($methods = [])
    {
        return $this->getMock('\JsonRpc\TransportInterface', $methods);
    }

    /**
     * @param $methods
     * @return \PHPUnit_Framework_MockObject_MockObject|Response
     */
    private function getResponseMock($methods = null)
    {
        return $this->getMock('\JsonRpc\Spec\Response', $methods, [], '', false);
    }
}
