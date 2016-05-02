<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Textmaster\Event\CallbackEvent;
use Textmaster\Events;
use Textmaster\Model\DocumentInterface;
use Textmaster\Translator\Adapter\AdapterInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;
use Worldia\Bundle\TextmasterBundle\Exception\NoResultException;

class DocumentListener implements EventSubscriberInterface
{
    /**
     * @var JobManagerInterface
     */
    protected $jobManager;

    /**
     * @var AdapterInterface[]
     */
    protected $adapters;

    /**
     * DocumentListener constructor.
     *
     * @param JobManagerInterface $jobManager
     * @param AdapterInterface[]  $adapters
     */
    public function __construct(JobManagerInterface $jobManager, array $adapters)
    {
        $this->jobManager = $jobManager;
        $this->adapters = $adapters;
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
        ];
    }

    /**
     * Create a job for the document.
     *
     * @param GenericEvent $event
     *
     * @throws \Exception
     */
    public function onTextmasterDocumentInCreation(GenericEvent $event)
    {
        /** @var DocumentInterface $document */
        $document = $event->getSubject();
        $translatable = $this->getSubjectFromDocument($document);
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
     * Mark document's related job as 'finished'.
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
     * Get through adapters to retrieve the subject from the given document.
     *
     * @param DocumentInterface $document
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function getSubjectFromDocument(DocumentInterface $document)
    {
        foreach ($this->adapters as $adapter) {
            try {
                $translatable = $adapter->getSubjectFromDocument($document);
                if (null !== $translatable) {
                    return $translatable;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new NoResultException(sprintf(
            'No subject for document "%s"',
            $document->getId()
        ));
    }
}
