<?php

namespace Worldia\TextmasterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WorldiaTextmasterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if ($config['httpclient_options']['enabled']) {
            $container->setParameter('worldia.textmaster.httpclient.options', $config['httpclient_options']);
        }

        $container->setParameter('worldia.textmaster.credentials.api_key', $config['credentials']['api_key']);
        $container->setParameter('worldia.textmaster.credentials.api_secret', $config['credentials']['api_secret']);

        $container->setParameter('worldia.textmaster.templates.project.index', $config['templates']['project']['index']);
        $container->setParameter('worldia.textmaster.templates.project.show', $config['templates']['project']['show']);
        $container->setParameter('worldia.textmaster.templates.document.index', $config['templates']['document']['index']);
        $container->setParameter('worldia.textmaster.templates.document.show', $config['templates']['document']['show']);
    }
}
