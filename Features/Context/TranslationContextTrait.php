<?php

namespace Worldia\Bundle\TextmasterBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\Translation\Engine;

trait TranslationContextTrait
{
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

        return $repository->findOneById($id);
    }

    /**
     * Return the translation engine.
     *
     * @return Engine
     */
    public function getTranslationEngine()
    {
        return $this->kernel->getContainer()->get('worldia.textmaster.manager.translation');
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
     * @Given I create a translation project for products with the following parameters:
     */
    public function createTranslationProject(TableNode $table)
    {
        $products = $this->getEntityManager()->getRepository('WorldiaProductTestBundle:Product')->findAll();
        foreach ($table->getHash() as $data) {
            $project = $this->getTranslationEngine()->create(
                $products,
                $data['name'],
                $data['languageFrom'],
                $data['languageTo'],
                $data['category'],
                $data['projectBriefing'],
                json_decode($data['options'], true)
            );
            $this->getTranslationEngine()->launch($project);
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
     * @Given I translate job :job
     */
    public function translateJob(JobInterface $job)
    {
        $this->getTranslationEngine()->translate($job);
    }
}
