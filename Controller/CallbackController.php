<?php

namespace Worldia\Bundle\TextmasterBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Textmaster\CallbackHandler;

class CallbackController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Endpoint for Textmaster API callback.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function callbackAction(Request $request)
    {
        $this->getHandler()->handleWebHook($request);

        return new Response();
    }

    /**
     * Get textmaster handler.
     *
     * @return CallbackHandler
     */
    protected function getHandler()
    {
        return $this->container->get('worldia.textmaster.api.handler');
    }
}
