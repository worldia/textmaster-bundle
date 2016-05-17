<?php

namespace Worldia\Bundle\TextmasterBundle\Controller;

use Doctrine\ORM\EntityManager;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;

class JobController extends AbstractController
{
    protected $resource = 'job';

    /**
     * @param Request $request
     *
     * @return Pagerfanta
     */
    protected function getResources(Request $request)
    {
        return $this->container->get('worldia.textmaster.repository.job')->createPaginator($this->getCriteria($request));
    }

    /**
     * @param Request $request
     *
     * @return JobInterface
     */
    protected function getResource(Request $request)
    {
        return $this->getEntityManager()->getRepository('WorldiaTextmasterBundle:Job')->find($request->get('id'));
    }

    /**
     * Get entity manager.
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('doctrine')->getManager();
    }
}
