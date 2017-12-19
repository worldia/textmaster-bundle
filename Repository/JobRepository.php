<?php

namespace Worldia\Bundle\TextmasterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class JobRepository extends EntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function createPaginator(array $criteria = [], array $sorting = [], array $joinCriteria = [])
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $this->applyJoin($queryBuilder, $joinCriteria);
        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return Pagerfanta
     */
    protected function getPaginator(QueryBuilder $queryBuilder)
    {
        // Use output walkers option in DoctrineORMAdapter should be false as it affects performance greatly (see #3775)
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, true, false));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
    {
        $object = $this->findAll()[0];

        foreach ($criteria as $property => $value) {
            if (method_exists($object, 'get'.ucfirst($property))) {
                $name = $this->getPropertyName($property);

                if (null === $value) {
                    $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
                } elseif (is_array($value)) {
                    $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
                } elseif ('' !== $value) {
                    $parameter = str_replace('.', '_', $property);
                    $queryBuilder
                        ->andWhere($queryBuilder->expr()->eq($name, ':'.$parameter))
                        ->setParameter($parameter, $value)
                    ;
                }
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $sorting
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = [])
    {
        foreach ($sorting as $property => $order) {
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $joinCriteria
     */
    protected function applyJoin(QueryBuilder $queryBuilder, array $joinCriteria)
    {
        if (array_key_exists('join', $joinCriteria)) {
            foreach ($joinCriteria['join'] as $join) {
                if (!empty($join['conditionType'])) {
                    $queryBuilder->leftJoin(
                        $join['joinTo'],
                        $join['alias'],
                        $join['conditionType'],
                        $queryBuilder->expr()->eq(
                            $join['condition']['x'],
                            $join['condition']['y']
                        )
                    );
                } else {
                    $queryBuilder->leftJoin(
                        $join['joinTo'],
                        $join['alias']
                    );
                }
            }
        }

        if (array_key_exists('where', $joinCriteria)) {
            foreach ($joinCriteria['where'] as $where) {
                if (!empty($where['value'])) {
                    $queryBuilder->andWhere($queryBuilder->expr()->eq($where['field'], $where['value']));
                }
            }
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPropertyName($name)
    {
        if (false === strpos($name, '.')) {
            return 'o'.'.'.$name;
        }

        return $name;
    }
}
