<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Textmaster\Client;
use Textmaster\Model\DocumentInterface;
use Textmaster\Model\Project;
use Textmaster\Model\ProjectInterface;
use Textmaster\Translator\TranslatorInterface;

class TranslationManager implements TranslationManagerInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * Engine constructor.
     *
     * @param Client                $client
     * @param TranslatorInterface   $translator
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Client $client,
        TranslatorInterface $translator,
        UrlGeneratorInterface $router
    ) {
        $this->client = $client;
        $this->translator = $translator;
        $this->router = $router;
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
            ->setCallback($this->generateProjectCallback())
        ;

        $project->save();
        $this->generateDocuments($project, $translatable);
        $project->launch();

        return $project;
    }

    /**
     * {@inheritdoc}
     */
    public function translate(DocumentInterface $document, $satisfaction = null, $message = null)
    {
        $this->translator->complete($document, $satisfaction = null, $message = null);
    }

    /**
     * Generate documents to add in the project according to its locales.
     *
     * @param ProjectInterface $project
     * @param object[]         $translatableList
     */
    protected function generateDocuments(ProjectInterface $project, array $translatableList)
    {
        $callback = $this->generateDocumentCallback($project);

        foreach ($translatableList as $translatable) {
            $params = [];
            $params['project'] = $project;
            $params['document'] = [
                'title' => $this->generateTitle($project, $translatable),
                'callback' => $callback,
            ];
            $this->translator->create($translatable, $params);
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
    protected function generateDocumentCallback(ProjectInterface $project)
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

    /**
     * Generate a default callback for project in progress.
     *
     * @return array
     */
    protected function generateProjectCallback()
    {
        return [
            ProjectInterface::STATUS_IN_PROGRESS => [
                'url' => $this->router->generate('worldia_textmaster_project_update'),
            ],
        ];
    }
}
