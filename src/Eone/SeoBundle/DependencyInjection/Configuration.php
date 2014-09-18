<?php

namespace Eone\SeoBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('eone_seo');

        $rootNode
            ->children()
                ->scalarNode('append')->defaultValue(true)->end()
                ->arrayNode('sitemap')
                    ->useAttributeAsKey('service')
                    ->prototype('array')
                        ->children()
                            ->floatNode('priority')->min(0)->max(1.0)->defaultValue(1.0)->end()
                            ->scalarNode('service')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
