<?php

namespace Worldia\Bundle\TextmasterBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Worldia\Bundle\TextmasterBundle\DependencyInjection\Compiler\AdapterCompilerPass;
use Worldia\Bundle\TextmasterBundle\DependencyInjection\Compiler\FactoryCompilerPass;

class WorldiaTextmasterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AdapterCompilerPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new FactoryCompilerPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
