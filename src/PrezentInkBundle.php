<?php

namespace Prezent\InkBundle;

use Prezent\InkBundle\DependencyInjection\Compiler\InkyComponentPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * PrezentInkBundle
 *
 * @see Bundle
 * @author Sander Marechal
 */
class PrezentInkBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new InkyComponentPass());
    }
}
