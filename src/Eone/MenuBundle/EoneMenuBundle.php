<?php

namespace Eone\MenuBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Eone\MenuBundle\DependencyInjection\Compiler\FindMenusCompilerPass;

class EoneMenuBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FindMenusCompilerPass());
    }
}
