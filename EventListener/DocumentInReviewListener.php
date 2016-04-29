<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Textmaster\Event\CallbackEvent;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;

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
     * @param CallbackEvent $event
     */
    public function onTextmasterDocumentInReview(CallbackEvent $event)
    {
        $job = $this->jobManager->getFromDocument($event->getSubject());
        $this->jobManager->finish($job);
    }
}
