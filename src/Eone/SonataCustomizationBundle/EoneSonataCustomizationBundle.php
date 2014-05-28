<?php

namespace Eone\SonataCustomizationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Eone\SonataCustomizationBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;

class EoneSonataCustomizationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
    }
}
