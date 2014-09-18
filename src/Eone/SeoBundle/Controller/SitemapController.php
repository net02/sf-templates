<?php

namespace Eone\SeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\Menu\ItemInterface;
use Eone\SeoBundle\Event\SitemapEvent;

class SitemapController extends Controller
{
    public function indexAction()
    {
        $expiredate = new \DateTime();
        $expiredate->setTimezone(new \DateTimeZone('GMT'));
        $expiredate->modify('+1 days');
        $expiredate->setTime(0, 0, 0);        
        
        $response = $this->render('EoneSeoBundle:Sitemap:sitemapindex.xml.twig', array('maps' => $this->container->getParameter('eone.locale.locales')));
        $response->setExpires($expiredate);
        
        return $response;
    }

    public function localizedAction(Request $request)
    {    
        $expiredate = new \DateTime();
        $expiredate->setTimezone(new \DateTimeZone('GMT'));
        $expiredate->modify('+1 days');
        $expiredate->setTime(0, 0, 0);
        
    	$urls = array();
        $locale = $request->getLocale();
        
        $this->get('eone.locale')->setLocale($locale);
        $config = $this->container->getParameter('eone.seo.sitemap.config');
        
        foreach ($config as $service => $cfg) {
            $menu = $this->container->get($service, ContainerInterface::NULL_ON_INVALID_REFERENCE);
            if ($menu && $menu instanceof ItemInterface) {
                $urls = array_merge($urls, $this->getMenuUrls($menu, $cfg['priority']));
            }
        }
        
        $event = $this->container->get('event_dispatcher')->dispatch('seo.sitemap', new SitemapEvent($locale));
        $urls = array_merge($urls, $event->getUrls());
        
        $response = $this->render('EoneSeoBundle:Sitemap:sitemap.xml.twig', array('urls' => $urls));
        $response->setExpires($expiredate);
        
        return $response;
    }
    
    private function getMenuUrls(ItemInterface $menu, $priority) {
        $urls = array();
        if ($menu->hasChildren() && $menu->getDisplayChildren()) {
            foreach ($menu->getChildren() as $child) {
                if ($child->isDisplayed()) {
                    $urls[] = array(
                        'loc'      => $child->getUri(),
                        'priority' => number_format($priority, 1, '.', ''),
                    );
                }
                $urls = array_merge($urls, $this->getMenuUrls($child, $priority));
            }
        }
        
        return $urls;
    }
}
