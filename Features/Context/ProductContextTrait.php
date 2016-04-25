<?php

namespace Worldia\Bundle\TextmasterBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert;
use Worldia\Bundle\ProductTestBundle\Entity\Product;

trait ProductContextTrait
{
    /**
     * @Transform :product
     *
     * @param string $id
     *
     * @return Product
     */
    public function findProduct($id)
    {
        $repository = $this->getEntityManager()->getRepository('WorldiaProductTestBundle:Product');

        return $repository->findOneById($id);
    }

    /**
     * @Given I have :number products
     */
    public function createProduct($number)
    {
        for ($i = 0; $i < $number; ++$i) {
            $product = new Product();
            $this->getEntityManager()->persist($product);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given I have the following translations for product :product:
     */
    public function createProductTranslations(Product $product, TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $productTranslation = $product->translate($data['locale']);
            $productTranslation->setTitle($data['title']);
            $productTranslation->setDescription($data['description']);

            $this->getEntityManager()->persist($productTranslation);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given I should have the following translations for product :product:
     */
    public function assertProductTranslations(Product $product, TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            PHPUnit_Framework_Assert::assertSame($data['title'], $product->getTranslation($data['locale'])->getTitle());
            PHPUnit_Framework_Assert::assertSame($data['description'], $product->getTranslation($data['locale'])->getDescription());
        }
    }
}
