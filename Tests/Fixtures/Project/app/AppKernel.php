<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Fixtures\Project\app;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle;
use Worldia\Bundle\ProductTestBundle\WorldiaProductTestBundle;
use Worldia\Bundle\TextmasterBundle\WorldiaTextmasterBundle;

class AppKernel extends BaseKernel
{
    private $mainBundle = 'WorldiaTextmasterBundle';

    /**
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new DoctrineBundle(),
            new FrameworkBundle(),
            new TwigBundle(),
            new WhiteOctoberPagerfantaBundle(),
            new WorldiaProductTestBundle(),
            new WorldiaTextmasterBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function getCacheDir()
    {
        return $this->guessTempDirectoryFor('cache');
    }

    public function getLogDir()
    {
        return $this->guessTempDirectoryFor('logs');
    }

    private function guessTempDirectoryFor($dirname)
    {
        $dir = is_writable(__DIR__.'/../../../../build/tmp') ?
            __DIR__.'/build/tmp/'.$dirname
            : sys_get_temp_dir().'/'.$this->mainBundle.'/'.$dirname;
            
            return $dir;
    }
}
