<?php

namespace Eone\BlockBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('eone_block');

        $rootNode
            ->children()
                ->scalarNode('base_template')->defaultValue('EoneBlockBundle:Block:block_orm.html.twig')->end()
                ->scalarNode('block_class')->defaultValue('Eone\BlockBundle\Entity\Block')->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
