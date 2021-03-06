<?php

namespace Worldia\Bundle\TextmasterBundle\EntityManager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Textmaster\Manager;
use Textmaster\Model\DocumentInterface;
use Textmaster\Model\ProjectInterface;
use Worldia\Bundle\TextmasterBundle\Entity\Job;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\Repository\JobRepository;

class JobManager implements JobManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Manager
     */
    protected $textmasterManager;

    /**
     * @var JobRepository
     */
    protected $jobRepository;

    /**
     * Translator constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Manager                $textmasterManager
     * @param JobRepository          $jobRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Manager $textmasterManager,
        JobRepository $jobRepository
    ) {
        $this->entityManager = $entityManager;
        $this->textmasterManager = $textmasterManager;
        $this->jobRepository = $jobRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function create($translatable, $projectId, $documentId, $locale)
    {
        $job = new Job($translatable, $projectId, $documentId, $locale);

        return $this->persistAndFlush($job);
    }

    /**
     * {@inheritdoc}
     */
    public function start(JobInterface $job)
    {
        return $this->updateStatus($job, JobInterface::STATUS_STARTED);
    }

    /**
     * {@inheritdoc}
     */
    public function startJobs(ProjectInterface $project)
    {
        $query = 'UPDATE Worldia\Bundle\TextmasterBundle\Entity\Job j '
            .'SET j.status = :status '
            .'WHERE j.projectId = :projectId ';
        $q = $this->entityManager
            ->createQuery($query)
            ->setParameter('status', JobInterface::STATUS_STARTED)
            ->setParameter('projectId', $project->getId());

        $q->execute();
        $this->entityManager->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function finish(JobInterface $job)
    {
        return $this->updateStatus($job, JobInterface::STATUS_FINISHED);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(JobInterface $job)
    {
        return $this->updateStatus($job, JobInterface::STATUS_VALIDATED);
    }

    /**
     * {@inheritdoc}
     */
    public function getFromDocument(DocumentInterface $document)
    {
        $job = $this->jobRepository->findOneBy(['documentId' => $document->getId()]);

        if (null === $job) {
            throw new NotFoundResourceException(sprintf(
                'No job for document "%s"',
                $document->getId()
            ));
        }

        return $job;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocument(JobInterface $job)
    {
        return $this->textmasterManager->getDocument($job->getProjectId(), $job->getDocumentId());
    }

    /**
     * {@inheritdoc}
     */
    public function getProject(JobInterface $job)
    {
        return $this->textmasterManager->getProject($job->getProjectId());
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslatablesWithJobAndLocale($class, $locale)
    {
        $result = $this->jobRepository
            ->createQueryBuilder('j')
            ->select('j.translatableId')
            ->where('j.translatableClass = :class')
            ->andWhere('j.locale = :locale')
            ->setParameter('class', $class)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getArrayResult()
        ;

        return array_map(function ($value) { return $value['translatableId']; }, $result);
    }

    /**
     * Update job status.
     *
     * @param JobInterface $job
     * @param string       $status
     *
     * @return JobInterface
     */
    protected function updateStatus(JobInterface $job, $status)
    {
        $job->setStatus($status);

        return $this->persistAndFlush($job);
    }

    /**
     * Persist the job and flush.
     *
     * @param JobInterface $job
     *
     * @return JobInterface
     */
    protected function persistAndFlush(JobInterface $job)
    {
        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }
}
