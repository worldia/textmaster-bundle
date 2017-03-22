<?php

namespace Worldia\Bundle\TextmasterBundle\Entity;

interface JobInterface
{
    const STATUS_CREATED = 'created';
    const STATUS_STARTED = 'started';
    const STATUS_FINISHED = 'finished';
    const STATUS_VALIDATED = 'validated';

    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Get translatable.
     *
     * @return object
     */
    public function getTranslatable();

    /**
     * Set the translatable.
     *
     * @param object $translatable
     *
     * @return JobInterface
     */
    public function setTranslatable($translatable);

    /**
     * Get translatable class.
     *
     * @return string
     */
    public function getTranslatableClass();

    /**
     * Get translatable id.
     *
     * @return int
     */
    public function getTranslatableId();

    /**
     * Get Textmaster project id.
     *
     * @return string
     */
    public function getProjectId();

    /**
     * Get Textmaster document id.
     *
     * @return string
     */
    public function getDocumentId();

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return JobInterface
     */
    public function setStatus($status);

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return JobInterface
     */
    public function setLocale($locale);

    /**
     * Get all allowed values for status property.
     *
     * @return array
     */
    public static function getAllowedStatus();
}
