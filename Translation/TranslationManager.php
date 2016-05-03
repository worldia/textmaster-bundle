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
     * Engine constructor.
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
        $languageTo,
        $category,
        $briefing,
        array $options = []
    ) {
        $project = $this->textmasterManager->getProject();
        $project
            ->setName($name)
            ->setActivity(ProjectInterface::ACTIVITY_TRANSLATION)
            ->setLanguageFrom($languageFrom)
            ->setLanguageTo($languageTo)
            ->setCategory($category)
            ->setBriefing($briefing)
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
     * Generate a document and add it in the project for each translatable.
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
     * @return string
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
                    'worldia_textmaster_callback_document',
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
                'url' => $this->router->generate('worldia_textmaster_callback_project'),
            ],
        ];
    }
}
