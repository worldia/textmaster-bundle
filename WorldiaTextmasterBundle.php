<?php

namespace Worldia\Bundle\TextmasterBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Worldia\Bundle\TextmasterBundle\DependencyInjection\Compiler\TranslatorCompilerPass;

class WorldiaTextmasterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TranslatorCompilerPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
