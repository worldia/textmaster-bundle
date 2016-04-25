<?php

namespace Worldia\Bundle\TextmasterBundle\Features\Context;

use Symfony\Component\HttpFoundation\Request;
use Textmaster\Handler;
use Worldia\Bundle\ProductTestBundle\Service\ProjectApi;

trait TextmasterContextTrait
{
    /**
     * @return ProjectApi
     */
    public function getProjectApi()
    {
        return $this->kernel->getContainer()->get('worldia.textmaster.project.api');
    }

    /**
     * @return Handler
     */
    public function getHandler()
    {
        return $this->kernel->getContainer()->get('worldia.textmaster.api.handler');
    }

    /**
     * @Given I receive the request :content
     */
    public function receiveDocumentInReviewEvent($content)
    {
        $this->getProjectApi()->documents()->updateDocument(json_decode($content, true));

        $request = new Request([], [], [], [], [], [], $content);
        $this->getHandler()->handleWebHook($request);
    }
}
