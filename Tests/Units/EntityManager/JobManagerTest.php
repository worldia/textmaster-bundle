<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\EntityManager;

use Worldia\Bundle\TextmasterBundle\Entity\Job;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManager;

class JobManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $entityManagerMock;
    protected $textmasterManagerMock;
    protected $jobManager;
    protected $translatableMock;

    public function setUp()
    {
        $this->entityManagerMock = $this->getMock('Doctrine\ORM\EntityManagerInterface');

        $this->textmasterManagerMock = $this
            ->getMockBuilder('Textmaster\Manager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->translatableMock = $this->getMock('TranslatableInterface', array('getId'));
        $this->translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->jobManager = new JobManager($this->entityManagerMock, $this->textmasterManagerMock);
    }

    /**
     * @test
     */
    public function shouldCreateJob()
    {
        $job = $this->jobManager->create($this->translatableMock, 'projectId', 'documentId');

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
        $job = new Job($this->translatableMock, 'projectId', 'documentId');
        $this->jobManager->start($job);

        $this->assertSame(JobInterface::STATUS_STARTED, $job->getStatus());
    }

    /**
     * @test
     */
    public function shouldFinishJob()
    {
        $job = new Job($this->translatableMock, 'projectId', 'documentId');
        $this->jobManager->finish($job);

        $this->assertSame(JobInterface::STATUS_FINISHED, $job->getStatus());
    }

    /**
     * @test
     */
    public function shouldValidateJob()
    {
        $documentMock = $this->getMock('Textmaster\Model\DocumentInterface');
        $this->textmasterManagerMock->expects($this->once())
            ->method('getDocument')
            ->willReturn($documentMock);

        $job = new Job($this->translatableMock, 'projectId', 'documentId');
        $this->jobManager->validate($job);

        $this->assertSame(JobInterface::STATUS_VALIDATED, $job->getStatus());
    }
}
