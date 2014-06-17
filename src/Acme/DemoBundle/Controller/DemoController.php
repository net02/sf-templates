<?php

namespace Acme\DemoBundle\Controller;

use Eone\SonataCustomizationBundle\Controller\TranslatingController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\ItemInterface;
use Eone\MenuBundle\Entity\MenuNode;

class DemoController extends TranslatingController
{
    public function menuAction() {
        return $this->render('AcmeDemoBundle::menu.html.twig');
    }
    
    public function switchAction(Request $request, ItemInterface $current = null) {
        // default route (usually a homepage) or null to use '/'
        $default_route = 'home';
        
        $nodes = array();
        if ($current) {
            $repo = $this->getDoctrine()->getManager()->getRepository('EoneMenuBundle:MenuNode');
            $node = $repo->findOneById($current->getExtra('node_id'));
            
            if ($node) {
                $service = $this->container->get('nmn.locale');
                foreach ($this->container->getParameter('locales_available') as $loc => $name) {
                    $service->setLocale($loc);
                    $node->setLocale($service);

                    if ($node->getActive()) {
                        $new = new MenuNode();
                        $nodes[$loc] = $new
                            ->setName($name)
                            ->setUri($node->getUri())
                            ->setRoute($node->getRoute())
                            ->setAbsolute($node->getAbsolute())
                            ->setRouteParams(array_merge($node->getRouteParams(), $node->getTranslatedParams(), ['_locale' => $loc]));
                    }
                }
            }
        }
        foreach ($this->container->getParameter('locales_available') as $loc => $name) {
            if (!isset($nodes[$loc])) {
                $new = new MenuNode();
                $nodes[$loc] = $new
                    ->setName($name)
                    ->setUri('/')
                    ->setRoute($default_route)
                    ->setRouteParams(['_locale' => $loc]);
            }
        }
        
        $menu = $this->container->get('eone.menu.builder')->createLanguageSwitchMenu($nodes, $request);        
        return $this->render('AcmeDemoBundle::language-switch.html.twig', array('menu' => $menu));
    }
}