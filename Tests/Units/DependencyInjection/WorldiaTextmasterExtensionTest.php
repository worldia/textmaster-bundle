<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Worldia\Bundle\TextmasterBundle\DependencyInjection\WorldiaTextmasterExtension;

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
            'copywriting_word_count' => 200,
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
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.document.index', 'WorldiaTextmasterBundle:Document:list.html.twig');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.project.show', 'WorldiaTextmasterBundle:Project:show.html.twig');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.project.index', 'WorldiaTextmasterBundle:Project:list.html.twig');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.job.show', 'WorldiaTextmasterBundle:Job:show.html.twig');
        $this->assertContainerBuilderHasParameter('worldia.textmaster.templates.job.index', 'WorldiaTextmasterBundle:Job:list.html.twig');

        $this->assertContainerBuilderHasParameter('worldia.textmaster.copywriting_word_count', 200);
    }

    /**
     * @test
     */
    public function assertMappingPropertiesParameters()
    {
        $mapping = [
            'Worldia\Bundle\ProductTestBundle\Entity\Product' => ['title', 'description'],
        ];
        $this->load(['mapping_properties' => $mapping]);

        $this->assertContainerBuilderHasParameter('worldia.textmaster.mapping.properties', $mapping);
    }

    /**
     * @test
     */
    public function assertHttpClientOptionsParameters()
    {
        $options = [
            'base_uri' => 'http://my.base.uri',
            'api_version' => '1',
        ];
        $this->load(['httpclient_options' => $options]);

        $this->assertContainerBuilderHasParameter(
            'worldia.textmaster.httpclient.options',
            array_merge(['enabled' => true], $options)
        );
    }

    /**
     * @test
     */
    public function assertServices()
    {
        $this->load();

        $this->assertContainerBuilderHasService('worldia.textmaster.api.httpclient', 'Textmaster\HttpClient\HttpClient');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.client', 'Textmaster\Client');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.manager', 'Textmaster\Manager');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.handler', 'Textmaster\CallbackHandler');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.translator', 'Textmaster\Translator\Translator');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.mapping_provider', 'Textmaster\Translator\Provider\ArrayBasedMappingProvider');
        $this->assertContainerBuilderHasService('worldia.textmaster.api.document_factory', 'Textmaster\Translator\Factory\DefaultDocumentFactory');

        $this->assertContainerBuilderHasService('worldia.textmaster.manager.job', 'Worldia\Bundle\TextmasterBundle\EntityManager\JobManager');
        $this->assertContainerBuilderHasService('worldia.textmaster.manager.translation', 'Worldia\Bundle\TextmasterBundle\Translation\TranslationManager');
        $this->assertContainerBuilderHasService('worldia.textmaster.listener.document', 'Worldia\Bundle\TextmasterBundle\EventListener\DocumentListener');
        $this->assertContainerBuilderHasService('worldia.textmaster.listener.project', 'Worldia\Bundle\TextmasterBundle\EventListener\ProjectListener');
        $this->assertContainerBuilderHasService('worldia.textmaster.listener.job', 'Worldia\Bundle\TextmasterBundle\EventListener\JobListener');
    }
}
