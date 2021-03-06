<?php

namespace Worldia\Bundle\TextmasterBundle\DependencyInjection;

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
                ->append($this->addTemplatesNode())
                ->scalarNode('dsn')->isRequired()->end()
                ->arrayNode('mapping_properties')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->requiresAtLeastOneElement()
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
                ->scalarNode('copywriting_word_count')->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addTemplatesNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('templates');

        $node
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
                            ->defaultValue('WorldiaTextmasterBundle:Document:list.html.twig')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('filter')
                            ->defaultValue('WorldiaTextmasterBundle:Document:filter.html.twig')
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
                            ->defaultValue('WorldiaTextmasterBundle:Project:list.html.twig')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('filter')
                            ->defaultValue('WorldiaTextmasterBundle:Project:filter.html.twig')
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('job')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('show')
                            ->defaultValue('WorldiaTextmasterBundle:Job:show.html.twig')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('index')
                            ->defaultValue('WorldiaTextmasterBundle:Job:list.html.twig')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('filter')
                            ->defaultValue('WorldiaTextmasterBundle:Job:filter.html.twig')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('compare')
                            ->defaultValue('WorldiaTextmasterBundle:Job:compare.html.twig')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('accept')
                            ->defaultValue('WorldiaTextmasterBundle:Job:accept.html.twig')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('reject')
                            ->defaultValue('WorldiaTextmasterBundle:Job:reject.html.twig')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
