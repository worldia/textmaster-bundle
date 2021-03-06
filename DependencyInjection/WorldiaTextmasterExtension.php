<?php

namespace Worldia\Bundle\TextmasterBundle\DependencyInjection;

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


        $container->setParameter('worldia.textmaster.templates.project.index', $config['templates']['project']['index']);
        $container->setParameter('worldia.textmaster.templates.project.show', $config['templates']['project']['show']);
        $container->setParameter('worldia.textmaster.templates.project.filter', $config['templates']['project']['filter']);
        $container->setParameter('worldia.textmaster.templates.document.index', $config['templates']['document']['index']);
        $container->setParameter('worldia.textmaster.templates.document.show', $config['templates']['document']['show']);
        $container->setParameter('worldia.textmaster.templates.document.filter', $config['templates']['document']['filter']);
        $container->setParameter('worldia.textmaster.templates.job.index', $config['templates']['job']['index']);
        $container->setParameter('worldia.textmaster.templates.job.show', $config['templates']['job']['show']);
        $container->setParameter('worldia.textmaster.templates.job.filter', $config['templates']['job']['filter']);
        $container->setParameter('worldia.textmaster.templates.job.compare', $config['templates']['job']['compare']);
        $container->setParameter('worldia.textmaster.templates.job.accept', $config['templates']['job']['accept']);
        $container->setParameter('worldia.textmaster.templates.job.reject', $config['templates']['job']['reject']);

        $container->setParameter('worldia.textmaster.dsn', $config['dsn']);
        $container->setParameter('worldia.textmaster.mapping.properties', $config['mapping_properties']);
        $container->setParameter('worldia.textmaster.copywriting_word_count', $config['copywriting_word_count']);
    }
}
