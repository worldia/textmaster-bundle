<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

use Textmaster\Model\ProjectInterface;

interface TranslationGeneratorInterface
{
    /**
     * Generate a textmaster project with documents for eligible objects.
     *
     * @param string $finderCode   translatable finder code
     * @param string $languageFrom source language identifier (ex: fr, en, de...)
     * @param array  $filter       array of parameters to filter the list of objects
     * @param string $name         textmaster project name
     * @param string $category     textmaster project category
     * @param string $briefing     textmaster project briefing
     * @param string $languageTo   destination language identifier (ex: fr, en, de...)
     * @param array  $options      textmaster project options
     * @param array  $activity     textmaster project activity
     * @param array  $workTemplate textmaster project work template
     *
     * @return ProjectInterface|null
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
        $activity,
        $workTemplate = null
    );
}
