<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Textmaster\Manager;
use Textmaster\Model\DocumentInterface;
use Textmaster\Model\ProjectInterface;
use Textmaster\Translator\TranslatorInterface;

class TranslationManager implements TranslationManagerInterface
{
    /**
     * @var Manager
     */
    protected $textmasterManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * TranslationManager constructor.
     *
     * @param Manager               $textmasterManager
     * @param TranslatorInterface   $translator
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Manager $textmasterManager,
        TranslatorInterface $translator,
        UrlGeneratorInterface $router
    ) {
        $this->textmasterManager = $textmasterManager;
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
        $category,
        $briefing,
        $languageTo = null,
        array $options = [],
        $activity = ProjectInterface::ACTIVITY_TRANSLATION
    ) {
        $project = $this->textmasterManager->getProject();
        $project
            ->setName($name)
            ->setActivity($activity)
            ->setLanguageFrom($languageFrom)
            ->setLanguageTo($languageTo)
            ->setCategory($category)
            ->setBriefing($briefing)
            ->setOptions($options)
            ->setCallback($this->generateProjectCallback())
        ;

        $project->save();
        $project->addDocuments($this->generateDocuments($project, $translatable));

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
     * Generate a document and add it in the project for each translatable.
     *
     * @param ProjectInterface $project
     * @param object[]         $translatableList
     *
     * @return array|DocumentInterface[]
     */
    protected function generateDocuments(ProjectInterface $project, array $translatableList)
    {
        $callback = $this->generateDocumentCallback($project);
        $activity = $project->getActivity();
        $documents = [];

        foreach ($translatableList as $translatable) {
            $params = [];
            $params['project'] = $project;
            $params['document'] = [
                'title' => $this->generateTitle($project, $translatable),
                'instructions' => $this->generateInstructions($translatable, $activity),
                'callback' => $callback,
            ];
            $documents[] = $this->translator->create($translatable, $params, false);
        }

        return $documents;
    }

    /**
     * Generate a default title for a document.
     *
     * @param ProjectInterface $project
     * @param object           $translatable
     *
     * @return string
     */
    protected function generateTitle(ProjectInterface $project, $translatable)
    {
        $middle = $project->getActivity();
        if ($project->getLanguageTo()) {
            $middle = $project->getLanguageTo();
        }

        return implode('-', [$project->getLanguageFrom(), $middle, $translatable->getId()]);
    }

    /**
     * Generate instructions for a document.
     *
     * @param object $translatable
     * @param string $activity
     *
     * @return string
     */
    protected function generateInstructions($translatable, $activity)
    {
        return '';
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
                    'worldia_textmaster_callback_document',
                    ['projectId' => $project->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
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
            ProjectInterface::CALLBACK_KEY => [
                'url' => $this->router->generate(
                    'worldia_textmaster_callback_project',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ],
        ];
    }
}
