<?php

namespace Worldia\Bundle\TextmasterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class DocumentController extends AbstractController
{
    protected $resource = 'document';

    /**
     * {@inheritdoc}
     */
    protected function getResources(Request $request)
    {
        return $this->getManager()->getProject($request->get('projectId'))->getDocuments($this->getCriteria($request));
    }

    /**
     * {@inheritdoc}
     */
    protected function getResource(Request $request)
    {
        return $this->getManager()->getDocument($request->get('projectId'), $request->get('id'));
    }
}
