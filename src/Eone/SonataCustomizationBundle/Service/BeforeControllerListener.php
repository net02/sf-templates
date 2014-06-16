<?php

namespace Eone\SonataCustomizationBundle\Service;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Bundle\WebProfilerBundle\Controller\ExceptionController;
use Eone\SonataCustomizationBundle\Controller\TranslatingController;

/**
 * @author Matt Drollette <matt@drollette.com>
 */
class BeforeControllerListener
{
    private $security_context;

    public function __construct(SecurityContextInterface $security_context)
    {
        $this->security_context = $security_context;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            // not a object but a different kind of callable. Do nothing
            return;
        }

        $controllerObject = $controller[0];

        // skip initializing for exceptions
        if ($controllerObject instanceof ExceptionController) {
            return;
        }

        if ($controllerObject instanceof TranslatingController) {
            // this method is the one that is part of the interface.
            $controllerObject->initialize($event->getRequest(), $this->security_context);
        }
    }
}