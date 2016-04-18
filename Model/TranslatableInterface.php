<?php

namespace Worldia\TextmasterBundle\Model;

interface TranslatableInterface
{
    /**
     * Translation helper method.
     *
     * @param string $locale
     *
     * @return TranslationInterface
     */
    public function translate($locale = null);

    /**
     * Get object properties to translate.
     *
     * @return array
     */
    public function getTranslatableProperties();
}
