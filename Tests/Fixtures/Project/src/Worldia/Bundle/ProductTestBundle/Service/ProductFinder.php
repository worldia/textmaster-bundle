<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;
use Worldia\Bundle\TextmasterBundle\Translation\TranslatableFinderInterface;

class ProductFinder implements TranslatableFinderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var JobManagerInterface
     */
    protected $jobManager;

    /**
     * @param EntityManagerInterface $manager
     * @param JobManagerInterface    $jobManager
     */
    public function __construct(EntityManagerInterface $manager, JobManagerInterface $jobManager)
    {
        $this->manager = $manager;
        $this->jobManager = $jobManager;
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
    public function find($locale, array $filter = [], $limit = null)
    {
        $qb = $this->manager->createQueryBuilder();
        $qb
            ->select('p')
            ->from('WorldiaProductTestBundle:Product', 'p')
        ;

        $ids = $this->jobManager->getTranslatablesWithJobAndLocale('Worldia\Bundle\ProductTestBundle\Entity\Product', $locale);
        if (0 < count($ids)) {
            $qb
                ->andWhere('p.id NOT IN (:ids)')
                ->setParameter('ids', $ids)
            ;
        }

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
