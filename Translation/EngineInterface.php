<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

use Textmaster\Model\ProjectInterface;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;

interface EngineInterface
{
    /**
     * Create a project with the given parameters.
     *
     * @param object[] $translatable
     * @param string   $name
     * @param string   $languageFrom
     * @param string   $languageTo
     * @param string   $category
     * @param string   $projectBriefing
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
        $projectBriefing,
        array $options = []
    );

    /**
     * Launch the given project.
     *
     * @param ProjectInterface $project
     *
     * @return ProjectInterface
     */
    public function launch(ProjectInterface $project);

    /**
     * Execute the given job translation process and validate it.
     *
     * @param JobInterface $job
     * @param string       $satisfaction
     * @param string       $message
     *
     * @return JobInterface
     */
    public function translate(JobInterface $job, $satisfaction = null, $message = null);
}
