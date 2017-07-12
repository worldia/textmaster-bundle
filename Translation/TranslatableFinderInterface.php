<?php

namespace Worldia\Bundle\TextmasterBundle\Translation;

interface TranslatableFinderInterface
{
    /**
     * Get code.
     *
     * @return string
     */
    public function getCode();

    /**
     * Find all entities to translate to the given locale.
     *
     * @param string $locale locale code (ex: fr_FR, en_US, de_DE...)
     * @param array  $filter array of parameters to filter the objects
     * @param array  $limit  max number of objects to retrieve
     *
     * @return object[]
     */
    public function find($locale, array $filter = [], $limit = null);
}
