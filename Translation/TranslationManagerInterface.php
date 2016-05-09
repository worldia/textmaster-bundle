<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

use Textmaster\Model\DocumentInterface;
use Textmaster\Model\ProjectInterface;

interface TranslationManagerInterface
{
    /**
     * Create a project with the given parameters.
     *
     * @param object[] $translatable
     * @param string   $name
     * @param string   $languageFrom
     * @param string   $languageTo
     * @param string   $category
     * @param string   $briefing
     * @param array    $options
     *
     * @return ProjectInterface
     */
    public function create(
        array $translatable,
        $name,
        $languageFrom,
        $languageTo,
        $category,
        $briefing,
        array $options = []
    );

    /**
     * Execute the given job translation process and validate it.
     *
     * @param DocumentInterface $document
     * @param string            $satisfaction
     * @param string            $message
     *
     * @return DocumentInterface
     */
    public function translate(DocumentInterface $document, $satisfaction = null, $message = null);
}
