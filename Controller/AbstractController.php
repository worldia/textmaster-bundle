<?php

namespace Worldia\Bundle\TextmasterBundle\Controller;

use Pagerfanta\Pagerfanta;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Textmaster\Manager;

abstract class AbstractController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $resource;

    /**
     * List all resources.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $pager = $this->getResources($request);
        $pager->setCurrentPage($request->get('page', 1));

        return $this->render('index', ['pager' => $pager]);
    }

    /**
     * Show a resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        return $this->render('show', ['resource' => $this->getResource($request)]);
    }

    /**
     * Get textmaster manager.
     *
     * @return Manager
     */
    protected function getManager()
    {
        return $this->container->get('worldia.textmaster.api.manager');
    }

    /**
     * @param $action
     *
     * @return string
     */
    protected function getTemplate($action)
    {
        return $this->container->getParameter(sprintf('worldia.textmaster.templates.%s.%s', $this->resource, $action));
    }

    /**
     * @param $action
     * @param array $params
     *
     * @return Response
     */
    protected function render($action, array $params = [])
    {
        return new Response($this->container->get('templating')->render($this->getTemplate($action), $params));
    }

    /**
     * @param Request $request
     *
     * @return Pagerfanta
     */
    abstract protected function getResources(Request $request);

    /**
     * @param Request $request
     */
    abstract protected function getResource(Request $request);
}
