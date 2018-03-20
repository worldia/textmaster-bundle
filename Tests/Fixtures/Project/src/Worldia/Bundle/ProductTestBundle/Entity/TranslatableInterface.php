<?php

namespace Worldia\Bundle\ProductTestBundle\Entity;

interface TranslatableInterface
{
    public function getId();
    
    /**
     * Translation method.
     *
     * @param string $locale
     *
     * @return ProductTranslation
     */
    public function translate($locale = null);
}
