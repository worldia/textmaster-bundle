<?php

namespace Worldia\Bundle\TextmasterBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit_Framework_Assert;
use Symfony\Component\HttpKernel\KernelInterface;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;
use Worldia\Bundle\TextmasterBundle\Translation\TranslationGeneratorInterface;
use Worldia\Bundle\TextmasterBundle\Translation\TranslationManagerInterface;

trait TranslationContextTrait
{
    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();

    /**
     * Returns HttpKernel instance.
     *
     * @return KernelInterface
     */
    abstract public function getKernel();

    /**
     * @return JobManagerInterface
     */
    public function getJobManager()
    {
        return $this->getKernel()->getContainer()->get('worldia.textmaster.manager.job');
    }

    /**
     * Return the translation manager.
     *
     * @return TranslationManagerInterface
     */
    public function getTranslationManager()
    {
        return $this->getKernel()->getContainer()->get('worldia.textmaster.manager.translation');
    }

    /**
     * Return the translation generator.
     *
     * @return TranslationGeneratorInterface
     */
    public function getTranslationGenerator()
    {
        return $this->getKernel()->getContainer()->get('worldia.textmaster.generator.translation');
    }

    /**
     * @Transform :job
     *
     * @param string $id
     *
     * @return JobInterface
     */
    public function findJob($id)
    {
        $repository = $this->getEntityManager()->getRepository('WorldiaTextmasterBundle:Job');

        return $repository->find($id);
    }

    /**
     * @Given I should have the following jobs:
     */
    public function assertJobs(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $job = $this->findJob($data['id']);

            PHPUnit_Framework_Assert::assertSame(JobInterface::STATUS_STARTED, $job->getStatus());
            PHPUnit_Framework_Assert::assertSame((int) $data['translatable'], $job->getTranslatable()->getId());
            PHPUnit_Framework_Assert::assertSame($data['project'], $job->getProjectId());
            PHPUnit_Framework_Assert::assertSame($data['document'], $job->getDocumentId());
        }
    }

    /**
     * @Given I generate a translation batch with the following parameters:
     */
    public function createTranslationProject(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->getTranslationGenerator()->generate(
                $data['finder'],
                [],
                $data['languageFrom'],
                $data['languageTo'],
                $data['name'],
                $data['category'],
                $data['briefing'],
                json_decode($data['options'], true)
            );
        }
    }

    /**
     * @Then the job :job should have status :status
     */
    public function assertJobStatus(JobInterface $job, $status)
    {
        PHPUnit_Framework_Assert::assertSame($status, $job->getStatus());
    }

    /**
     * @Then I should have :number translatables with job for class :class
     */
    public function assertTranslatablesWithJob($number, $class)
    {
        $count = count($this->getJobManager()->getTranslatablesWithJob($class));

        PHPUnit_Framework_Assert::assertSame((int) $number, $count);
    }

    /**
     * @Given I translate job :job
     */
    public function translateJob(JobInterface $job)
    {
        $document = $this->getJobManager()->getDocument($job);
        $this->getTranslationManager()->translate($document);
    }
}
