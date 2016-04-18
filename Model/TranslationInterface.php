<?php

namespace Worldia\TextmasterBundle\Model;

interface TranslationInterface
{
    /**
     * @return TranslatableInterface
     */
    public function getTranslatable();

    /**
     * @param null|TranslatableInterface $translatable
     */
    public function setTranslatable(TranslatableInterface $translatable = null);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @param string $locale
     */
    public function setLocale($locale);

    /**
     * Get Textmaster document id.
     *
     * @return string
     */
    public function getDocumentId();

    /**
     * Set Textmaster document id.
     *
     * @param $id
     *
     * @return TranslationInterface
     */
    public function setDocumentId($id);
}
