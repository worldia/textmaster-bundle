<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

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
        $languageTo,
        $name,
        $category,
        $briefing,
        array $options = []
    ) {
        $translatables = $this->translatableFinders[$finderCode]->find($languageTo, $filter);

        if (!count($translatables)) {
            return false;
        }

        $project = $this->translationManager->create($translatables, $name, $languageFrom, $languageTo, $category, $briefing, $options);

        return $project->launch();
    }
}
