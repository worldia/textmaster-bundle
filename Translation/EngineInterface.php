<?php

namespace Worldia\TextmasterBundle\Translation;

use Textmaster\Model\DocumentInterface;
use Textmaster\Model\ProjectInterface;
use Worldia\TextmasterBundle\Model\TranslatableInterface;

interface EngineInterface
{
    /**
     * Generate a textmaster Project to translate the given translatables.
     * For each Translatable:
     * - generate a Translation for the given "language to"
     * - generate a Document for the Translation with all translatable properties.
     *
     * @param TranslatableInterface[] $translatables
     * @param string                  $name
     * @param string                  $languageFrom
     * @param string                  $languageTo
     * @param string                  $category
     * @param string                  $projectBriefing
     * @param array                   $options
     *
     * @return ProjectInterface
     */
    public function createProject(
        array $translatables,
        $name,
        $languageFrom,
        $languageTo,
        $category,
        $projectBriefing,
        array $options = []
    );

    /**
     * Complete the given project.
     * Go through all documents to complete them.
     *
     * @param ProjectInterface $project
     *
     * @return ProjectInterface
     */
    public function completeProject(ProjectInterface $project);

    /**
     * Complete the given document.
     * Update the related translation with the document translated content.
     *
     * @param DocumentInterface $document
     *
     * @return DocumentInterface
     */
    public function completeDocument(DocumentInterface $document);
}
