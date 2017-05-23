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

            PHPUnit_Framework_Assert::assertSame($data['status'], $job->getStatus());
            PHPUnit_Framework_Assert::assertSame($data['locale'], $job->getLocale());
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
            $project = $this->getTranslationGenerator()->generate(
                $data['finder'],
                json_decode($data['filter'], true),
                $data['languageFrom'],
                $data['name'],
                $data['category'],
                $data['briefing'],
                isset($data['languageTo']) ? $data['languageTo'] : null,
                json_decode($data['options'], true),
                isset($data['activity']) ? $data['activity'] : null,
                isset($data['workTemplate']) ? $data['workTemplate'] : null,
                json_decode($data['useMyTextmasters'])
            );

            if (null !== $project) {
                PHPUnit_Framework_Assert::assertSame($data['name'], $project->getName());
                PHPUnit_Framework_Assert::assertSame(isset($data['activity']) ? $data['activity'] : null, $project->getActivity());
                PHPUnit_Framework_Assert::assertSame($data['languageFrom'], $project->getLanguageFrom());
                PHPUnit_Framework_Assert::assertSame(isset($data['languageTo']) ? $data['languageTo'] : null, $project->getLanguageTo());
                PHPUnit_Framework_Assert::assertSame($data['category'], $project->getCategory());
                PHPUnit_Framework_Assert::assertSame($data['briefing'], $project->getBriefing());
                PHPUnit_Framework_Assert::assertSame(json_decode($data['options'], true), $project->getOptions());
            }
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
     * @Then I should have :number translatables with job for class :class and locale :locale
     */
    public function assertTranslatablesWithJob($number, $class, $locale)
    {
        $count = count($this->getJobManager()->getTranslatablesWithJobAndLocale($class, $locale));

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
