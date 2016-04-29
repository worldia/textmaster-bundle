<?php

namespace Worldia\Bundle\TextmasterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class TranslatorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $translator = $container->getDefinition('worldia.textmaster.api.translator');

        $this->addAdapters($container, $translator);
        $this->addFactory($container, $translator);
    }

    /**
     * Add adapters.
     *
     * @param ContainerBuilder $container
     * @param Definition       $translator
     *
     * @throws LogicException
     */
    private function addAdapters(ContainerBuilder $container, Definition $translator)
    {
        $adapters = [];
        foreach ($container->findTaggedServiceIds('textmaster_translator_adapter') as $id => $service) {
            $adapters[] = $container->getDefinition($id);
        }

        if (0 === count($adapters)) {
            throw new LogicException('You need at least one textmaster_translator_adapter.');
        }

        $translator->replaceArgument(0, $adapters);
    }

    /**
     * Add factory.
     *
     * @param ContainerBuilder $container
     * @param Definition       $translator
     *
     * @throws LogicException
     */
    private function addFactory(ContainerBuilder $container, Definition $translator)
    {
        $factoryServices = $container->findTaggedServiceIds('textmaster_translator_factory');
        if (0 === count($factoryServices)) {
            return;
        }

        if (1 < count($factoryServices)) {
            throw new LogicException('You cannot have more than one textmaster_translator_factory.');
        }

        foreach ($factoryServices as $id => $service) {
            $translator->addArgument($container->getDefinition($id));
        }
    }
}
