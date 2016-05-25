<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    private $mainBundle = 'WorldiaTextmasterBundle';

    /**
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Worldia\Bundle\ProductTestBundle\WorldiaProductTestBundle(),
            new Worldia\Bundle\TextmasterBundle\WorldiaTextmasterBundle(),
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
        return is_writable(__DIR__.'/../../../../build/tmp') ?
            __DIR__.'/build/tmp/'.$dirname
            : sys_get_temp_dir().'/'.$this->mainBundle.'/'.$dirname;
    }
}
