<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\EntityManager;

use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManager;

class JobManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCreateJob()
    {
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $textmasterManagerMock = $this
            ->getMockBuilder('Textmaster\Manager')
            ->disableOriginalConstructor()
            ->getMock();
        $translatableMock = $this->getMock('TranslatableInterface', array('getId'));

        $translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $jobManager = new JobManager($entityManagerMock, $textmasterManagerMock);
        $job = $jobManager->create($translatableMock, 'projectId', 'documentId');

        $this->assertTrue(in_array('Worldia\Bundle\TextmasterBundle\Entity\JobInterface', class_implements($job)));
        $this->assertSame(JobInterface::STATUS_CREATED, $job->getStatus());
        $this->assertSame('projectId', $job->getProjectId());
        $this->assertSame('documentId', $job->getDocumentId());
    }

    /**
     * @test
     */
    public function shouldStartJob()
    {
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $textmasterManagerMock = $this
            ->getMockBuilder('Textmaster\Manager')
            ->disableOriginalConstructor()
            ->getMock();
        $translatableMock = $this->getMock('TranslatableInterface', array('getId'));

        $translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $jobManager = new JobManager($entityManagerMock, $textmasterManagerMock);
        $job = $jobManager->create($translatableMock, 'projectId', 'documentId');
        $jobManager->start($job);

        $this->assertSame(JobInterface::STATUS_STARTED, $job->getStatus());
    }

    /**
     * @test
     */
    public function shouldFinishJob()
    {
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $textmasterManagerMock = $this
            ->getMockBuilder('Textmaster\Manager')
            ->disableOriginalConstructor()
            ->getMock();
        $translatableMock = $this->getMock('TranslatableInterface', array('getId'));

        $translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $jobManager = new JobManager($entityManagerMock, $textmasterManagerMock);
        $job = $jobManager->create($translatableMock, 'projectId', 'documentId');
        $jobManager->finish($job);

        $this->assertSame(JobInterface::STATUS_FINISHED, $job->getStatus());
    }

    /**
     * @test
     */
    public function shouldValidateJob()
    {
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $textmasterManagerMock = $this
            ->getMockBuilder('Textmaster\Manager')
            ->disableOriginalConstructor()
            ->getMock();
        $translatableMock = $this->getMock('TranslatableInterface', array('getId'));
        $documentMock = $this->getMock('Textmaster\Model\DocumentInterface');

        $translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $textmasterManagerMock->expects($this->once())
            ->method('getDocument')
            ->willReturn($documentMock);

        $jobManager = new JobManager($entityManagerMock, $textmasterManagerMock);
        $job = $jobManager->create($translatableMock, 'projectId', 'documentId');
        $jobManager->validate($job);

        $this->assertSame(JobInterface::STATUS_VALIDATED, $job->getStatus());
    }
}
