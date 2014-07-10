<?php

namespace Eone\SonataCustomizationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EoneSonataCustomizationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        if (!$container->hasParameter('locales_available')) {
            throw new \RuntimeException('Parameter "locales_available" must be set.');
        }
        // prepare locale requirements for routing purposes
        $container->setParameter('eone.locale.requirements', implode('|', array_keys($container->getParameter('locales_available'))));
        $container->setParameter('eone.locale.locales', array_keys($container->getParameter('locales_available')));
        
        $container->setParameter('eone.sonatacustomization.configuration.templates', $config['templates']);
    }
}
