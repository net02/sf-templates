<?php

namespace Eone\SonataCustomizationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * GlobalVariablesCompilerPass
 *
 * @author Daniele Bartocci <net02.it@gmail.com>
 */
class GlobalVariablesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('twig')
            ->addMethodCall('addGlobal', array('sonata_customization', new Reference('eone.sonatacustomization.twig.global')));
    }
}
