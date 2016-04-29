<?php

namespace Worldia\Bundle\TextmasterBundle\Features\Context;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;

    use ProductContextTrait;
    use TranslationContextTrait;
    use TextmasterContextTrait;

    /**
     * @BeforeScenario
     */
    public function buildSchema()
    {
        $entityManager = $this->getEntityManager();
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $tool = new SchemaTool($entityManager);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    /**
     * Return the object persistence manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
    }
}
