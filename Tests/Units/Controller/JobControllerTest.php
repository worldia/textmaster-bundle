<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Worldia\Bundle\ProductTestBundle\Entity\Product;
use Worldia\Bundle\TextmasterBundle\Entity\Job;

class JobControllerTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setUp()
    {
        $client = self::createClient();

        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $tool = new SchemaTool($this->entityManager);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    /**
     * @test
     */
    public function testPageJobIndexIsSuccessful()
    {
        $client = self::createClient();
        $client->request('GET', '/jobs');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @test
     */
    public function testPageJobShowIsSuccessful()
    {
        $product = new Product();
        $this->persist($product);
        $job = new Job($product, 1, 1, 'fr');
        $this->persist($job);

        $client = self::createClient();
        $client->request('GET', '/jobs/1');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Persist the given object and flush.
     *
     * @param object $object
     */
    protected function persist($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
