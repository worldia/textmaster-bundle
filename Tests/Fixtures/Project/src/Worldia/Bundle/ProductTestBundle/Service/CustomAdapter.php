<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Textmaster\Translator\Adapter\AbstractDoctrineAdapter;

class CustomAdapter extends AbstractDoctrineAdapter
{
    protected $interface = 'Worldia\Bundle\ProductTestBundle\Entity\TranslatableInterface';

    /**
     * Get object holding translated properties:
     * 1/ Used to get values in source language
     * 2/ Used to set values in destination language.
     *
     * @param object $subject
     * @param string $language
     *
     * @return mixed
     */
    protected function getPropertyHolder($subject, $language)
    {
        return $subject->translate($language);
    }
}
