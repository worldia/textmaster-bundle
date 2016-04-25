<?php

namespace Worldia\Bundle\TextmasterBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;

class JobListener implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
        ];
    }

    /**
     * Load translatable.
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof JobInterface || null === $translatableClass = $entity->getTranslatableClass()) {
            return;
        }

        $translatable = $args
            ->getObjectManager()
            ->getRepository($translatableClass)
            ->find($entity->getTranslatableId())
        ;

        $entity->setTranslatable($translatable);
    }
}
