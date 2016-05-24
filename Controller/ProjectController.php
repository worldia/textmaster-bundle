<?php

namespace Worldia\Bundle\TextmasterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractController
{
    protected $resource = 'project';

    /**
     * {@inheritdoc}
     */
    protected function getResources(Request $request)
    {
        $defaults = ['archived' => false];
        $criteria = array_merge($defaults, $request->query->get('criteria', []));
        $request->query->set('criteria', $criteria);

        return $this->getManager()->getProjects($this->getCriteria($request));
    }

    /**
     * {@inheritdoc}
     */
    protected function getResource(Request $request)
    {
        return $this->getManager()->getProject($request->get('id'));
    }
}
