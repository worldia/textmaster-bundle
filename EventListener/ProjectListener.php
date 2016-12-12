<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Textmaster\Event\CallbackEvent;
use Textmaster\Events;
use Textmaster\Model\ProjectInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;

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
            Events::PROJECT_IN_PROGRESS => 'onTextmasterProjectInProgress',
            Events::PROJECT_MEMORY_COMPLETED => 'onTextmasterProjectTmCompleted',
        ];
    }

    /**
     * Mark project's related job as 'started'.
     *
     * @param CallbackEvent $event
     */
    public function onTextmasterProjectInProgress(CallbackEvent $event)
    {
        /** @var ProjectInterface $project */
        $project = $event->getSubject();
        $this->jobManager->startJobs($project);
    }

    /**
     * Launch project.
     *
     * @param CallbackEvent $event
     */
    public function onTextmasterProjectTmCompleted(CallbackEvent $event)
    {
        /** @var ProjectInterface $project */
        $project = $event->getSubject();
        $project->launch();
    }
}
