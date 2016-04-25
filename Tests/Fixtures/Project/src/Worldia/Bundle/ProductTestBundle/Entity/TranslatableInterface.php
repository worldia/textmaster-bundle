<?php

namespace Worldia\Bundle\ProductTestBundle\Entity;

interface TranslatableInterface
{
    /**
     * Translation method.
     *
     * @param string $locale
     *
     * @return ProductTranslation
     */
    public function translate($locale = null);
}
