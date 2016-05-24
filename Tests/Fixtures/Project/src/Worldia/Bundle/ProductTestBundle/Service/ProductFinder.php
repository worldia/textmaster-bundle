<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Worldia\Bundle\TextmasterBundle\Translation\TranslatableFinderInterface;

class ProductFinder implements TranslatableFinderInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * ProductFinder constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function find($locale, array $filter = [])
    {
        return $this->registry->getRepository('WorldiaProductTestBundle:Product')->findAll();
    }
}
