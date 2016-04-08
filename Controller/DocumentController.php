<?php

namespace Worldia\TextmasterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class DocumentController extends AbstractController
{
    protected $resource = 'document';

    /**
     * {@inheritdoc}
     */
    protected function getResources(Request $request)
    {
        return $this->getManager()->getProject($request->query->get('projectId'))->getDocuments();
    }

    /**
     * {@inheritdoc}
     */
    protected function getResource(Request $request)
    {
        return $this->getManager()->getDocument($request->query->get('projectId'), $request->query->get('id'));
    }
}
