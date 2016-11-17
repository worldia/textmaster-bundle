<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Textmaster\Exception\BadMethodCallException;
use Textmaster\Model\DocumentInterface;
use Textmaster\Translator\Adapter\AbstractDoctrineAdapter;

class CustomAdapter extends AbstractDoctrineAdapter
{
    protected $interface = 'Worldia\Bundle\ProductTestBundle\Entity\TranslatableInterface';

    /**
     * {@inheritdoc}
     */
    public function complete(DocumentInterface $document, $satisfaction = null, $message = null)
    {
        $subject = $this->getSubjectFromDocument($document);
        $this->failIfDoesNotSupport($subject);

        /** @var array $properties */
        $properties = $document->getSourceContent();

        $project = $document->getProject();
        $language = $this->getLanguageTo($project);

        $this->setProperties($subject, $properties, $language);

        try {
            $document->complete($satisfaction, $message);
        } catch (BadMethodCallException $e) {
        }

        $this->persist($subject);

        return $subject;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPropertyHolder($subject, $language, $activity = null)
    {
        return $subject->translate($language);
    }
}
