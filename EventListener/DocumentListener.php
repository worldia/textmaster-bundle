<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Textmaster\Event\CallbackEvent;
use Textmaster\Events as TextmasterEvents;
use Textmaster\Model\DocumentInterface;
use Textmaster\Translator\TranslatorInterface;
use Worldia\Bundle\TextmasterBundle\Events;
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
            TextmasterEvents::DOCUMENT_IN_CREATION => 'onTextmasterDocumentInCreation',
            TextmasterEvents::DOCUMENT_COMPLETED => 'onTextmasterDocumentCompleted',
            TextmasterEvents::DOCUMENT_INCOMPLETE => 'onTextmasterDocumentIncomplete',
            Events::DOCUMENT_IN_REVIEW => 'onCallbackDocumentInReview',
            Events::DOCUMENT_COMPLETED => 'onCallbackDocumentCompleted',
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

    /**
     * Mark document's related job as 'finished'.
     *
     * @param CallbackEvent $event
     */
    public function onCallbackDocumentInReview(CallbackEvent $event)
    {
        /** @var DocumentInterface $document */
        $document = $event->getSubject();
        $job = $this->jobManager->getFromDocument($document);
        $this->jobManager->finish($job);
    }

    /**
     * Complete document.
     *
     * @param CallbackEvent $event
     */
    public function onCallbackDocumentCompleted(CallbackEvent $event)
    {
        /** @var DocumentInterface $document */
        $document = $event->getSubject();
        $job = $this->jobManager->getFromDocument($document);

        $this->translator->complete($document);
        $this->jobManager->validate($job);
    }
}
