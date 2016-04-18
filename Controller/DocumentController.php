<?php

namespace Worldia\TextmasterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentController
{
    /**
     * List all documents in the given project.
     *
     * @param string $projectId
     *
     * @return Response
     */
    public function indexAction($projectId)
    {
    }

    /**
     * Endpoint for Document API callbacks.
     *
     * @param Request $request
     */
    public function callbackAction(Request $request)
    {
    }
}
