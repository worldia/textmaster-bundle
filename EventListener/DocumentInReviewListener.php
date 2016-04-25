<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Textmaster\Event\DocumentEvent;
use Worldia\Bundle\TextmasterBundle\Manager\JobManagerInterface;

class DocumentInReviewListener
{
    /**
     * @var JobManagerInterface
     */
    protected $jobManager;

    /**
     * DocumentInReviewListener constructor.
     *
     * @param JobManagerInterface $jobManager
     */
    public function __construct(JobManagerInterface $jobManager)
    {
        $this->jobManager = $jobManager;
    }

    /**
     * Mark document's related job as 'finished'.
     *
     * @param DocumentEvent $event
     */
    public function onTextmasterDocumentInReview(DocumentEvent $event)
    {
        $job = $this->jobManager->getFromDocument($event->getDocument());
        $this->jobManager->finish($job);
    }
}
