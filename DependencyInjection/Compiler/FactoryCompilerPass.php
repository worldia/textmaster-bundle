<?php

namespace Worldia\Bundle\TextmasterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class FactoryCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $factoryServices = $container->findTaggedServiceIds('textmaster_translator_factory');
        if (0 === count($factoryServices)) {
            return;
        }

        if (1 < count($factoryServices)) {
            throw new LogicException('You cannot have more than one textmaster_translator_factory.');
        }

        $translator = $container->getDefinition('worldia.textmaster.api.translator');
        foreach ($factoryServices as $id => $service) {
            $translator->addArgument($container->getDefinition($id));
        }
    }
}
