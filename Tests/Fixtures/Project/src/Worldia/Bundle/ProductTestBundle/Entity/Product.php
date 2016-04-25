<?php

namespace Worldia\Bundle\ProductTestBundle\Entity;

class Product implements TranslatableInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $translations;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Translation method.
     *
     * @param string $locale
     *
     * @return ProductTranslation
     */
    public function translate($locale = null)
    {
        if (null === $translation = $this->getTranslation($locale)) {
            $translation = new ProductTranslation();
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }

        return $translation;
    }

    /**
     * Add a translation.
     *
     * @param ProductTranslation $translation
     */
    public function addTranslation(ProductTranslation $translation)
    {
        $this->translations[$translation->getLocale()] = $translation;
        $translation->setTranslatable($this);
    }

    /**
     * Get translation for the given locale.
     *
     * @param string $locale
     *
     * @return ProductTranslation
     */
    public function getTranslation($locale)
    {
        return $this->translations[$locale];
    }
}
