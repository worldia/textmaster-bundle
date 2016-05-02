<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\EventListener;

use Textmaster\Events;
use Worldia\Bundle\TextmasterBundle\EventListener\DocumentListener;

class DocumentListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DocumentListener
     */
    protected $listener;

    protected $jobManagerMock;
    protected $adapterMock;
    protected $eventMock;
    protected $documentMock;

    public function setUp()
    {
        $this->jobManagerMock = $this->getMock('Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface');
        $this->adapterMock = $this->getMock('Textmaster\Translator\Adapter\AdapterInterface');

        $this->eventMock = $this->getMock('Symfony\Component\EventDispatcher\GenericEvent');
        $this->documentMock = $this->getMock('Textmaster\Model\DocumentInterface');

        $this->listener = new DocumentListener($this->jobManagerMock, [$this->adapterMock]);
    }

    /**
     * @test
     */
    public function shouldGetSubscribedEvents()
    {
        $events = [
            Events::DOCUMENT_IN_CREATION => 'onTextmasterDocumentInCreation',
            Events::DOCUMENT_IN_REVIEW => 'onTextmasterDocumentInReview',
            Events::DOCUMENT_COMPLETED => 'onTextmasterDocumentCompleted',
        ];

        $this->assertSame(DocumentListener::getSubscribedEvents(), $events);
    }

    /**
     * @test
     */
    public function shouldCreateJobWhenDocumentInCreation()
    {
        $objectMock = $this->getMock('ObjectInterface');
        $projectMock = $this->getMock('Textmaster\Model\ProjectInterface');

        $this->eventMock->expects($this->once())
            ->method('getSubject')
            ->willReturn($this->documentMock);

        $this->adapterMock->expects($this->once())
            ->method('getSubjectFromDocument')
            ->willReturn($objectMock);

        $this->documentMock->expects($this->once())
            ->method('getProject')
            ->willReturn($projectMock);

        $this->jobManagerMock->expects($this->once())
            ->method('create');

        $this->listener->onTextmasterDocumentInCreation($this->eventMock);
    }

    /**
     * @test
     */
    public function shouldFinishJobWhenDocumentInReview()
    {
        $eventMock = $this->getMockBuilder('Textmaster\Event\CallbackEvent')->disableOriginalConstructor()->getMock();
        $jobMock = $this->getMock('Worldia\Bundle\TextmasterBundle\Entity\JobInterface');

        $eventMock->expects($this->once())
            ->method('getSubject')
            ->willReturn($this->documentMock);

        $this->jobManagerMock->expects($this->once())
            ->method('getFromDocument')
            ->willReturn($jobMock);

        $this->jobManagerMock->expects($this->once())
            ->method('finish');

        $this->listener->onTextmasterDocumentInReview($eventMock);
    }

    /**
     * @test
     */
    public function shouldValidateJobWhenDocumentCompleted()
    {
        $jobMock = $this->getMock('Worldia\Bundle\TextmasterBundle\Entity\JobInterface');

        $this->eventMock->expects($this->once())
            ->method('getSubject')
            ->willReturn($this->documentMock);

        $this->jobManagerMock->expects($this->once())
            ->method('getFromDocument')
            ->willReturn($jobMock);

        $this->jobManagerMock->expects($this->once())
            ->method('validate');

        $this->listener->onTextmasterDocumentCompleted($this->eventMock);
    }

    /**
     * @test
     * @expectedException \Worldia\Bundle\TextmasterBundle\Exception\NoResultException
     */
    public function shouldNotCreateJobWhenDocumentInCreationHasNoSubject()
    {
        $this->eventMock->expects($this->once())
            ->method('getSubject')
            ->willReturn($this->documentMock);

        $this->adapterMock->expects($this->once())
            ->method('getSubjectFromDocument')
            ->willReturn(null);

        $this->listener->onTextmasterDocumentInCreation($this->eventMock);
    }
}
