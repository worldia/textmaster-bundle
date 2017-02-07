<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

use Textmaster\Model\ProjectInterface;

class TranslationGenerator implements TranslationGeneratorInterface
{
    /**
     * @var TranslatableFinderInterface[]
     */
    protected $translatableFinders;

    /**
     * @var TranslationManagerInterface
     */
    protected $translationManager;

    /**
     * TranslationGenerator constructor.
     *
     * @param TranslationManagerInterface $translationManager
     */
    public function __construct(TranslationManagerInterface $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    /**
     * Add a translatable finder.
     *
     * @param TranslatableFinderInterface $translatableFinder
     */
    public function addTranslatableFinder(TranslatableFinderInterface $translatableFinder)
    {
        $this->translatableFinders[$translatableFinder->getCode()] = $translatableFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(
        $finderCode,
        array $filter = [],
        $languageFrom,
        $name,
        $category,
        $briefing,
        $languageTo = null,
        array $options = [],
        $activity = ProjectInterface::ACTIVITY_TRANSLATION,
        $workTemplate = null,
        $textmasters = []
    ) {
        if (null === $locale = $languageTo) {
            $locale = $languageFrom;
        }
        $translatables = $this->translatableFinders[$finderCode]->find($locale, $filter);

        if (!count($translatables)) {
            return;
        }

        $project = $this->translationManager->create($translatables, $name, $languageFrom, $category, $briefing, $languageTo, $options, $activity, $workTemplate, $textmasters);

        if (!array_key_exists('translation_memory', $project->getOptions())) {
            $project->launch();
        }

        return $project;
    }
}
