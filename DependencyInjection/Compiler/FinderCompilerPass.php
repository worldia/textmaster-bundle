<?php

namespace Worldia\Bundle\TextmasterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

class FinderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $finderServices = $container->findTaggedServiceIds('textmaster_translatable_finder');

        if (1 > count($finderServices)) {
            throw new LogicException('You need at least one textmaster_translatable_finder.');
        }

        $translationGenerator = $container->getDefinition('worldia.textmaster.generator.translation');
        foreach ($finderServices as $id => $service) {
            $translationGenerator->addMethodCall('addTranslatableFinder', [new Reference($id)]);
        }
    }
}
