<?php

namespace Worldia\TextmasterBundle\Tests\Units\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Worldia\TextmasterBundle\DependencyInjection\WorldiaTextmasterExtension;

class WorldiaTextmasterExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new WorldiaTextmasterExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMinimalConfiguration()
    {
        return [
            'credentials' => [
                'api_key' => 'My API key',
                'api_secret' => 'My API secret',
            ],
        ];
    }

    /**
     * @test
     */
    public function assertParameters()
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('worldia.textmaster.credentials.api_key', 'My API key');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.credentials.api_secret', 'My API secret');

        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.document.show', 'WorldiaTextmasterBundle:Document:show.html.twig');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.document.index', 'WorldiaTextmasterBundle::list.html.twig');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.project.show', 'WorldiaTextmasterBundle:Project:show.html.twig');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.project.index', 'WorldiaTextmasterBundle::list.html.twig');
    }

    /**
     * @test
     */
    public function assertServices()
    {
        $this->load();

        $this->assertContainerBuilderHasService('worldia.textmaster.api.client', 'Textmaster\Client');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.manager', 'Textmaster\Manager');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.handler', 'Textmaster\Handler');
    }
}
