<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Textmaster\Client;
use Textmaster\Model\DocumentInterface;
use Textmaster\Model\Project;
use Textmaster\Model\ProjectInterface;
use Textmaster\Translator\TranslatorInterface;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;

class TranslationManager implements TranslationManagerInterface
{
    /**
     * @var JobManagerInterface
     */
    protected $jobManager;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Engine constructor.
     *
     * @param JobManagerInterface   $jobManager
     * @param Client                $client
     * @param UrlGeneratorInterface $router
     * @param TranslatorInterface   $translator
     */
    public function __construct(
        JobManagerInterface $jobManager,
        Client $client,
        UrlGeneratorInterface $router,
        TranslatorInterface $translator
    ) {
        $this->jobManager = $jobManager;
        $this->client = $client;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        array $translatable,
        $name,
        $languageFrom,
        $languageTo,
        $category,
        $projectBriefing,
        array $options = []
    ) {
        $project = new Project($this->client);
        $project
            ->setName($name)
            ->setActivity(ProjectInterface::ACTIVITY_TRANSLATION)
            ->setLanguageFrom($languageFrom)
            ->setLanguageTo($languageTo)
            ->setCategory($category)
            ->setBriefing($projectBriefing)
            ->setOptions($options)
        ;

        $project->save();
        $this->generateDocuments($project, $translatable);

        return $project;
    }

    /**
     * {@inheritdoc}
     */
    public function launch(ProjectInterface $project)
    {
        $project->launch();
        $this->jobManager->startJobs($project);

        return $project;
    }

    /**
     * {@inheritdoc}
     */
    public function translate(JobInterface $job, $satisfaction = null, $message = null)
    {
        if (JobInterface::STATUS_FINISHED !== $job->getStatus()) {
            throw new \BadMethodCallException(sprintf(
                'Can only translate a finished job. Status is "%s".',
                $job->getStatus()
            ));
        }

        $this->translator->complete($this->jobManager->getDocument($job));
        $this->jobManager->validate($job, $satisfaction, $message);
    }

    /**
     * Generate documents to add in the project according to its locales.
     *
     * @param ProjectInterface $project
     * @param object[]         $translatableList
     */
    protected function generateDocuments(ProjectInterface $project, array $translatableList)
    {
        $projectId = $project->getId();
        $callback = $this->generateCallback($project);

        foreach ($translatableList as $translatable) {
            $params['project'] = $project;
            $params['document'] = [
                'title' => $this->generateTitle($project, $translatable),
                'callback' => $callback,
            ];
            $document = $this->translator->create($translatable, $params);
            $this->jobManager->create($translatable, $projectId, $document->getId());
        }
    }

    /**
     * Generate a default title for a document.
     *
     * @param ProjectInterface $project
     * @param object           $translatable
     *
     * @return array
     */
    protected function generateTitle(ProjectInterface $project, $translatable)
    {
        return implode('-', [$project->getLanguageFrom(), $project->getLanguageTo(), $translatable->getId()]);
    }

    /**
     * Generate a default callback for document in review.
     *
     * @param ProjectInterface $project
     *
     * @return array
     */
    protected function generateCallback(ProjectInterface $project)
    {
        return [
            DocumentInterface::STATUS_IN_REVIEW => [
                'url' => $this->router->generate(
                    'worldia_textmaster_document_update',
                    ['projectId' => $project->getId()]
                ),
            ],
        ];
    }
}
