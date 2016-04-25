<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Textmaster\Client;
use Textmaster\HttpClient\HttpClient;

class FakeClient extends Client
{
    protected $container;

    public function __construct(HttpClient $httpClient, Container $container)
    {
        $this->container = $container;
    }

    public function projects()
    {
        return $this->container->get('worldia.textmaster.project.api');
    }
}
