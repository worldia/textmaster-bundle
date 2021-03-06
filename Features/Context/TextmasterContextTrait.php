<?php

namespace Worldia\Bundle\TextmasterBundle\Features\Context;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Textmaster\CallbackHandler;
use Worldia\Bundle\ProductTestBundle\Service\ProjectApi;

trait TextmasterContextTrait
{
    /**
     * Returns HttpKernel instance.
     *
     * @return KernelInterface
     */
    abstract public function getKernel();

    /**
     * @return ProjectApi
     */
    public function getProjectApi()
    {
        return $this->getKernel()->getContainer()->get('worldia.textmaster.project.api');
    }

    /**
     * @return CallbackHandler
     */
    public function getHandler()
    {
        return $this->getKernel()->getContainer()->get('worldia.textmaster.api.handler');
    }

    /**
     * @Given I receive the request :content
     */
    public function receiveProjectRequest($content)
    {
        $data = json_decode($content, true);
        if (array_key_exists('name', $data)) {
            $this->getProjectApi()->updateProject(json_decode($content, true));
        } elseif (array_key_exists('original_content', $data) || array_key_exists('author_work', $data)) {
            $this->getProjectApi()->documents()->updateDocument(json_decode($content, true));
        }

        $request = new Request([], [], [], [], [], [], $content);
        $this->getHandler()->handleWebHook($request);
    }
}
