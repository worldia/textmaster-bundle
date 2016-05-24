<?php

namespace Worldia\Bundle\TextmasterBundle\EntityManager;

use Textmaster\Model\DocumentInterface;
use Textmaster\Model\ProjectInterface;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;

interface JobManagerInterface
{
    /**
     * Create a new job.
     *
     * @param object $translatable
     * @param string $projectId
     * @param string $documentId
     *
     * @return JobInterface
     */
    public function create($translatable, $projectId, $documentId);

    /**
     * Set given job status to 'started'.
     *
     * @param JobInterface $job
     *
     * @return JobInterface
     */
    public function start(JobInterface $job);

    /**
     * Set all project's jobs status to 'started'.
     *
     * @param ProjectInterface $project
     */
    public function startJobs(ProjectInterface $project);

    /**
     * Set given job status to 'finished'.
     *
     * @param JobInterface $job
     *
     * @return JobInterface
     */
    public function finish(JobInterface $job);

    /**
     * Set given job status to 'validated'.
     *
     * @param JobInterface $job
     *
     * @return JobInterface
     */
    public function validate(JobInterface $job);

    /**
     * Get job related to the given document.
     *
     * @param DocumentInterface $document
     *
     * @return JobInterface
     */
    public function getFromDocument(DocumentInterface $document);

    /**
     * Get textmaster document of the given job.
     *
     * @param JobInterface $job
     *
     * @return DocumentInterface
     */
    public function getDocument(JobInterface $job);

    /**
     * Get textmaster project of the given job.
     *
     * @param JobInterface $job
     *
     * @return ProjectInterface
     */
    public function getProject(JobInterface $job);

    /**
     * Get ids for objects of the given class which have a job.
     *
     * @param string $class
     *
     * @return array
     */
    public function getTranslatablesWithJob($class);
}
