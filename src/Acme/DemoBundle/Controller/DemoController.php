<?php

namespace Acme\DemoBundle\Controller;

use Eone\SonataCustomizationBundle\Controller\TranslatingController;

class DemoController extends TranslatingController
{
    public function menuAction() {
        return $this->render('AcmeDemoBundle::menu.html.twig');
    }
}