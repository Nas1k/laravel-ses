<?php

namespace Nas1k\LaravelSes\Test\Domain;

use Nas1k\LaravelSes\Domain\Entity\ReportRepository;

class ReportRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        /** @var \Nas1k\LaravelSes\Domain\Entity\Report $report */
        $report = $this->getMockBuilder('Nas1k\LaravelSes\Domain\Entity\Report')
            ->disableOriginalConstructor()
            ->getMock();
        /** @var \Doctrine\ORM\EntityManager|\PHPUnit_Framework_MockObject_MockObject $em */
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->once())
            ->method('persist')
            ->with($report);
        $em->expects($this->once())
            ->method('flush');

        (new ReportRepository($em, $metadata))->save($report);
    }
}
