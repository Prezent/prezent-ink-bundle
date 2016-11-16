<?php

namespace Prezent\InkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add inky components
 *
 * @see CompilerPassInterface
 * @author Sander Marechal
 */
class InkyComponentPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('prezent_ink.inky')) {
            return;
        }

        $definition = $container->findDefinition('prezent_ink.inky');
        $taggedServices = $container->findTaggedServiceIds('prezent_ink.inky_component');

        $components = [];

        foreach ($taggedServices as $id => $tags) {
            $components[] = new Reference($id);
        }

        $definition->replaceArgument(1, $components);
    }
}
