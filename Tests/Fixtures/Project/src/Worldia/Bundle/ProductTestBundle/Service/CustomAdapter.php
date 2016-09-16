<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Textmaster\Translator\Adapter\AbstractDoctrineAdapter;

class CustomAdapter extends AbstractDoctrineAdapter
{
    protected $interface = 'Worldia\Bundle\ProductTestBundle\Entity\TranslatableInterface';

    /**
     * {@inheritdoc}
     */
    protected function getPropertyHolder($subject, $language, $activity = null)
    {
        return $subject->translate($language);
    }
}
