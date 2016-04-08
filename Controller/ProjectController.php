<?php

namespace Worldia\TextmasterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractController
{
    protected $resource = 'project';

    /**
     * {@inheritdoc}
     */
    protected function getResources(Request $request)
    {
        return $this->getManager()->getProjects();
    }

    /**
     * {@inheritdoc}
     */
    protected function getResource(Request $request)
    {
        return $this->getManager()->getProject($request->query->get('id'));
    }
}
