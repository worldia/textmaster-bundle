<?php

namespace Worldia\Bundle\TextmasterBundle\Controller;

use Pagerfanta\Pagerfanta;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * Render filter form.
     *
     * @param string $type
     *
     * @return Response
     */
    public function filterAction($type)
    {
        $request = $this->container->get('request_stack')->getMasterRequest();

        $form = $this->container->get('form.factory')->createNamed('criteria', $type, null, ['method' => 'GET']);

        $form->handleRequest($request);

        return $this->render('filter', ['form' => $form->createView()]);
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
     * @param string $action
     *
     * @return string
     */
    protected function getTemplate($action)
    {
        return $this->container->getParameter(sprintf('worldia.textmaster.templates.%s.%s', $this->resource, $action));
    }

    /**
     * @param string $action
     * @param array  $params
     *
     * @return Response
     */
    protected function render($action, array $params = [])
    {
        return new Response($this->container->get('templating')->render($this->getTemplate($action), $params));
    }

    /**
     * @param string $route
     * @param array  $params
     *
     * @return Response
     */
    protected function redirect($route, array $params = [])
    {
        return new RedirectResponse($this->container->get('router')->generate($route, $params));
    }

    /**
     * Extract 'criteria' parameter from the request.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function getCriteria(Request $request)
    {
        $criteria = $request->query->get('criteria', []);
        unset($criteria['_token'], $criteria['filter']);

        return $criteria;
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
