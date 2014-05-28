<?php

namespace Eone\SonataCustomizationBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * GlobalVariables
 *
 * @author Daniele Bartocci <net02.it@gmail.com>
 */
class GlobalVariables
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getBaseTemplate()
    {
        return $this->container->get('sonata.admin.pool')->getTemplate('layout');
    }

    /**
     * @return string
     */
    public function getAdminPool()
    {
        return $this->container->get('sonata.admin.pool');
    }
}
