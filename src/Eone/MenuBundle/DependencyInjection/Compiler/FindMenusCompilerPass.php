<?php

namespace Eone\MenuBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * GlobalVariablesCompilerPass
 *
 * @author Daniele Bartocci <net02.it@gmail.com>
 */
class FindMenusCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $id = $container->getParameter('eone.menu.configuration.admin_service');
        if (!$container->hasDefinition($id)) {
            return;
        }
        
        $admin = $container->getDefinition($id);        
        $menus = array();
        foreach ($container->findTaggedServiceIds('eone_menu.menu') as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $menus[] = $attributes["alias"];
            }
        }
        
        $admin->addMethodCall(
            'setAvailableMenus',
            array($menus)
        );
    }
}
