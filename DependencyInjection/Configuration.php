<?php

namespace Worldia\TextmasterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('worldia_textmaster');

        $rootNode
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('document')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('show')
                                    ->defaultValue('WorldiaTextmasterBundle:Document:show.html.twig')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('index')
                                    ->defaultValue('WorldiaTextmasterBundle::list.html.twig')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->children()
                        ->arrayNode('project')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('show')
                                    ->defaultValue('WorldiaTextmasterBundle:Project:show.html.twig')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('index')
                                    ->defaultValue('WorldiaTextmasterBundle::list.html.twig')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('credentials')
                    ->children()
                        ->scalarNode('api_key')
                            ->isRequired()
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('api_secret')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
