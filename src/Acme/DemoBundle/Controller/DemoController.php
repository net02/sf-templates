<?php

namespace Acme\DemoBundle\Controller;

use Eone\SonataCustomizationBundle\Controller\TranslatingController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\ItemInterface;
use Eone\MenuBundle\Entity\MenuNode;

class DemoController extends TranslatingController
{
    /**
     * Default route (usually a homepage). Set null to use '/'
     */
    const HOMEPAGE_ROUTE = 'home';
    
    public function menuAction() {
        return $this->render('AcmeDemoBundle::menu.html.twig');
    }
    
    /**
     * Instantiate the language switch menu (to be served in a twig controller)
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Knp\Menu\ItemInterface $current
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function switchAction(Request $request, ItemInterface $current = null) {        
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
                    ->setRoute(self::HOMEPAGE_ROUTE)
                    ->setRouteParams(['_locale' => $loc]);
            }
        }
        
        $menu = $this->container->get('eone.menu.builder')->createLanguageSwitchMenu($nodes, $request);        
        return $this->render('AcmeDemoBundle::language-switch.html.twig', array('menu' => $menu));
    }
}