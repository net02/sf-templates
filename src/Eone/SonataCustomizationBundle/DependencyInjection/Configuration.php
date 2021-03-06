<?php

namespace Eone\SonataCustomizationBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('eone_sonatacustomization');

        $rootNode
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('browser')->defaultValue('EoneSonataCustomizationBundle:Ckeditor:browser.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('upload')->defaultValue('EoneSonataCustomizationBundle:Ckeditor:upload.html.twig')->cannotBeEmpty()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
