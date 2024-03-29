<?php

namespace Prezent\InkBundle\Tests\Fixture;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Sander Marechal
 */
class AppKernel extends Kernel
{
    private string $testCase;

    public function __construct($testCase, $env, $debug)
    {
        $this->testCase = $testCase;

        parent::__construct($env, $debug);
    }

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Prezent\InkBundle\PrezentInkBundle(),
            new \Prezent\InkBundle\Tests\Fixture\AppBundle\AppBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');

        // Disable logger, it makes testing error pages noisy
        $loader->load(function ($container) {
            $container->register('logger', 'Psr\Log\NullLogger');
        });
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$this->testCase.'/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$this->testCase.'/logs';
    }
}

if (!class_exists('AppKernel')) {
    class_alias(AppKernel::class, 'AppKernel');
}
