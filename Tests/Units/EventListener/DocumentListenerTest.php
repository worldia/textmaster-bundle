<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\EventListener;

use PHPUnit\Framework\TestCase;
use Textmaster\Events;
use Textmaster\Model\Project;
use Worldia\Bundle\TextmasterBundle\EventListener\DocumentListener;

class DocumentListenerTest extends TestCase
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
        $this->jobManagerMock = $this->createMock('Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface');
        $this->translatorMock = $this->createMock('Textmaster\Translator\TranslatorInterface');

        $this->eventMock = $this->createMock('Symfony\Component\EventDispatcher\GenericEvent');
        $this->documentMock = $this->createMock('Textmaster\Model\DocumentInterface');

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
            Events::DOCUMENT_INCOMPLETE => 'onTextmasterDocumentIncomplete',
        ];

        $this->assertSame(DocumentListener::getSubscribedEvents(), $events);
    }

    /**
     * @test
     */
    public function shouldCreateJobWhenDocumentInCreation()
    {
        $objectMock = $this->createMock('Worldia\Bundle\ProductTestBundle\Entity\Product');
        $projectMock = $this->createMock('Textmaster\Model\ProjectInterface');

        $this->eventMock->expects($this->once())
            ->method('getSubject')
            ->willReturn($this->documentMock);

        $this->translatorMock->expects($this->once())
            ->method('getSubjectFromDocument')
            ->willReturn($objectMock);

        $this->documentMock->expects($this->exactly(3))
            ->method('getProject')
            ->willReturn($projectMock);

        $projectMock->expects($this->once())
            ->method('getLanguageFrom')
            ->willReturn('fr');
        $projectMock->expects($this->once())
            ->method('getActivity')
            ->willReturn(Project::ACTIVITY_COPYWRITING);

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
        $jobMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Entity\JobInterface');

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
        $jobMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Entity\JobInterface');

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
     */
    public function shouldStartJobWhenDocumentIncomplete()
    {
        $jobMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Entity\JobInterface');

        $this->eventMock->expects($this->once())
            ->method('getSubject')
            ->willReturn($this->documentMock);

        $this->jobManagerMock->expects($this->once())
            ->method('getFromDocument')
            ->willReturn($jobMock);

        $this->jobManagerMock->expects($this->once())
            ->method('start');

        $this->listener->onTextmasterDocumentIncomplete($this->eventMock);
    }
}
