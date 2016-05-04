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
    protected $translatorMock;
    protected $eventMock;
    protected $documentMock;

    public function setUp()
    {
        $this->jobManagerMock = $this->getMock('Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface');
        $this->translatorMock = $this->getMock('Textmaster\Translator\TranslatorInterface');

        $this->eventMock = $this->getMock('Symfony\Component\EventDispatcher\GenericEvent');
        $this->documentMock = $this->getMock('Textmaster\Model\DocumentInterface');

        $this->listener = new DocumentListener($this->jobManagerMock, $this->translatorMock);
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

        $this->translatorMock->expects($this->once())
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
}