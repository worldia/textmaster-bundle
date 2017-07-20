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
     * @var int
     */
    protected $defaultWordCount;

    /**
     * TranslationManager constructor.
     *
     * @param Manager               $textmasterManager
     * @param TranslatorInterface   $translator
     * @param UrlGeneratorInterface $router
     * @param int                   $defaultWordCount
     */
    public function __construct(
        Manager $textmasterManager,
        TranslatorInterface $translator,
        UrlGeneratorInterface $router,
        $defaultWordCount
    ) {
        $this->textmasterManager = $textmasterManager;
        $this->translator = $translator;
        $this->router = $router;
        $this->defaultWordCount = $defaultWordCount;
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
        $activity = ProjectInterface::ACTIVITY_TRANSLATION,
        $workTemplate = null,
        $useMyTextmasters = true
    ) {
        $translationMemory = false;
        if (array_key_exists('translation_memory', $options)) {
            $translationMemory = true;
            unset($options['translation_memory']);
        }

        $project = $this->textmasterManager->getProject();
        $project
            ->setName($name)
            ->setActivity($activity)
            ->setLanguageFrom($languageFrom)
            ->setLanguageTo($languageTo)
            ->setCategory($category)
            ->setBriefing($briefing)
            ->setOptions($options)
            ->setWorkTemplate($workTemplate)
            ->setCallback($this->generateProjectCallback())
        ;

        $project->save();

        if ($useMyTextmasters) {
            $authors = $project->getPotentialAuthors('my_textmaster');

            $ids = array_map(function ($author) {
                return $author->getAuthorId();
            }, $authors);

            $project->setTextmasters($ids);
            $project->save();
        }

        $project->addDocuments($this->generateDocuments($project, $translatable));

        if ($translationMemory) {
            $options['translation_memory'] = true;
            $project->setOptions($options);
            $project->save();
        }

        return $project;
    }

    /**
     * {@inheritdoc}
     */
    public function translate(DocumentInterface $document, $satisfaction = null, $message = null)
    {
        $this->translator->complete($document);
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
                'word_count' => ProjectInterface::ACTIVITY_COPYWRITING === $activity ? $this->getWordCount($translatable) : 0,
            ];
            $documents[] = $this->translator->push($translatable, $params, false);
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
     * Get word count for a copywriting document.
     *
     * @param object $translatable
     *
     * @return string
     */
    protected function getWordCount($translatable)
    {
        return $this->defaultWordCount;
    }

    /**
     * Generate a default callback for document.
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
                    ['projectId' => $project->getId(), 'event' => DocumentInterface::STATUS_IN_REVIEW],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ],
            DocumentInterface::STATUS_COMPLETED => [
                'url' => $this->router->generate(
                    'worldia_textmaster_callback_document',
                    ['projectId' => $project->getId(), 'event' => DocumentInterface::STATUS_COMPLETED],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ],
        ];
    }

    /**
     * Generate a default callback for project.
     *
     * @return array
     */
    protected function generateProjectCallback()
    {
        return [
            ProjectInterface::CALLBACK_PROJECT_IN_PROGRESS => [
                'url' => $this->router->generate(
                    'worldia_textmaster_callback_project',
                    ['event' => ProjectInterface::STATUS_IN_PROGRESS],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ],
            ProjectInterface::CALLBACK_PROJECT_MEMORY_COMPLETED => [
                'url' => $this->router->generate(
                    'worldia_textmaster_callback_project',
                    ['event' => ProjectInterface::STATUS_MEMORY_COMPLETED],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ],
        ];
    }
}
