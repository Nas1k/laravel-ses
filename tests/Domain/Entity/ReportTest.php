<?php

namespace Nas1k\LaravelSes\Test\Domain;

use Nas1k\LaravelSes\Domain\Entity\Report;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidStatus()
    {
        (new Report())->setStatus('invalid');
    }

    public function testJsonSerialization()
    {
        $status = Report::STATUS_SUCCESS;
        $id = 123;
        /** @var \Nas1k\LaravelSes\Domain\EmailBuilder|\PHPUnit_Framework_MockObject_MockObject $message */
        $message = $this->getMockBuilder('Nas1k\LaravelSes\Domain\EmailBuilder')
            ->setMethods(['serialize', 'unserialize'])
            ->disableOriginalConstructor()
            ->getMock();
        $message->expects($this->once())
            ->method('serialize')
            ->willReturn('data');
        $message->expects($this->any())
            ->method('unserialize')
            ->with('data')
            ->willReturn('unserializeData');

        $report = new Report();
        $data = json_encode(
            $report->setStatus($status)
                ->setId($id)
                ->setMessage($message)
        );
        $result = json_decode($data);
        $this->assertEquals($result->id, $report->getId());
        $this->assertEquals($result->status, $report->getStatus());
        $this->assertEquals(unserialize($result->message), $report->getMessage());
    }
}
