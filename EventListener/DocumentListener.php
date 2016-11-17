<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Textmaster\Event\CallbackEvent;
use Textmaster\Events;
use Textmaster\Model\DocumentInterface;
use Textmaster\Translator\TranslatorInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;

class DocumentListener implements EventSubscriberInterface
{
    /**
     * @var JobManagerInterface
     */
    protected $jobManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * DocumentListener constructor.
     *
     * @param JobManagerInterface $jobManager
     * @param TranslatorInterface $translator
     */
    public function __construct(JobManagerInterface $jobManager, TranslatorInterface $translator)
    {
        $this->jobManager = $jobManager;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::DOCUMENT_IN_CREATION => 'onTextmasterDocumentInCreation',
            Events::DOCUMENT_IN_REVIEW => 'onTextmasterDocumentInReview',
            Events::DOCUMENT_COMPLETED => 'onTextmasterDocumentCompleted',
            Events::DOCUMENT_INCOMPLETE => 'onTextmasterDocumentIncomplete',
        ];
    }

    /**
     * Create a job for the document.
     *
     * @param GenericEvent $event
     */
    public function onTextmasterDocumentInCreation(GenericEvent $event)
    {
        /** @var DocumentInterface $document */
        $document = $event->getSubject();
        $translatable = $this->translator->getSubjectFromDocument($document);
        $this->jobManager->create($translatable, $document->getProject()->getId(), $document->getId());
    }

    /**
     * Mark document's related job as 'finished'.
     *
     * @param CallbackEvent $event
     */
    public function onTextmasterDocumentInReview(CallbackEvent $event)
    {
        /** @var DocumentInterface $document */
        $document = $event->getSubject();
        $job = $this->jobManager->getFromDocument($document);
        $this->jobManager->finish($job);
    }

    /**
     * Mark document's related job as 'validated'.
     *
     * @param GenericEvent $event
     */
    public function onTextmasterDocumentCompleted(GenericEvent $event)
    {
        /** @var DocumentInterface $document */
        $document = $event->getSubject();
        $job = $this->jobManager->getFromDocument($document);
        $this->jobManager->validate($job);
        $this->translator->pull($document);
    }

    /**
     * Mark document's related job as 'started'.
     *
     * @param GenericEvent $event
     */
    public function onTextmasterDocumentIncomplete(GenericEvent $event)
    {
        /** @var DocumentInterface $document */
        $document = $event->getSubject();
        $job = $this->jobManager->getFromDocument($document);
        $this->jobManager->start($job);
    }
}
