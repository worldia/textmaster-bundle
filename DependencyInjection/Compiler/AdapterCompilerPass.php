<?php

namespace Worldia\Bundle\TextmasterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class AdapterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $translator = $container->getDefinition('worldia.textmaster.api.translator');
        $documentListener = $container->getDefinition('worldia.textmaster.listener.document');

        $adapters = [];
        foreach ($container->findTaggedServiceIds('textmaster_translator_adapter') as $id => $service) {
            $adapters[] = $container->getDefinition($id);
        }

        if (0 === count($adapters)) {
            throw new LogicException('You need at least one textmaster_translator_adapter.');
        }

        $translator->replaceArgument(0, $adapters);
        $documentListener->replaceArgument(1, $adapters);
    }
}
