<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Textmaster\Client;
use Textmaster\HttpClient\HttpClient;

class FakeClient extends Client
{
    protected $container;

    public function __construct(HttpClient $httpClient, EventDispatcherInterface $dispatcher, Container $container)
    {
        parent::__construct($httpClient, $dispatcher);

        $this->container = $container;
    }

    public function projects()
    {
        return $this->container->get('worldia.textmaster.project.api');
    }
}
