<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Textmaster\Event\CallbackEvent;
use Textmaster\Model\ProjectInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;
use Worldia\Bundle\TextmasterBundle\Events;

class ProjectListener implements EventSubscriberInterface
{
    /**
     * @var JobManagerInterface
     */
    protected $jobManager;

    /**
     * ProjectListener constructor.
     *
     * @param JobManagerInterface $jobManager
     */
    public function __construct(JobManagerInterface $jobManager)
    {
        $this->jobManager = $jobManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::PROJECT_IN_PROGRESS => 'onCallbackProjectInProgress',
        ];
    }

    /**
     * Mark project's related job as 'started'.
     *
     * @param CallbackEvent $event
     */
    public function onCallbackProjectInProgress(CallbackEvent $event)
    {
        /** @var ProjectInterface $project */
        $project = $event->getSubject();
        $this->jobManager->startJobs($project);
    }
}
