<?php

namespace Eone\MenuBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class MenuBuilder
{
    private $factory;
    
    /**
     * @var EntityManager 
     */
    protected $em;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }
    
    /**
     * Creates a menu based off its service Alias
     * 
     * @param string $alias Must match service tag definition
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenuByAlias($alias, Request $request) {
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri());
        
        $repo = $this->em->getRepository("EoneMenuBundle:MenuNode");
        
        foreach($repo->findRootByAlias($alias)->getChildren() as $node) {
            if ($node->getActive()) {
                $menu->addChild($this->factory->createFromArray($node->toArray()));
            }
        }
        return $menu;
    }
    
    public function createLanguageSwitchMenu(array $nodes, Request $request) {
        $menu = $this->factory->createItem('root');
        
        foreach($nodes as $loc => $node) {
            $child = $this->factory->createFromArray($node->toArray(0));
            if ($request->getLocale() === $loc) {
                $child->setCurrent(true);
            }
            // remove unnecessary "_locale" parameter from query string
            $child->setUri(self::removeQuerystringVar($child->getUri(), '_locale'));
            $menu->addChild($child);
        }
        return $menu;
    }
    
    public static function removeQuerystringVar($url, $key) {
        $parts = parse_url($url);
        $qs = isset($parts['query']) ? $parts['query'] : '';
        $base = $qs ? mb_substr($url, 0, mb_strpos($url, '?')) : $url; // all of URL except QS

        parse_str($qs, $qsParts);
        unset($qsParts[$key]);
        $newQs = rtrim(http_build_query($qsParts), '=');

        if ($newQs) {
            return $base . '?' . $newQs;
        } 
        else {
            return $base;
        }
    }
}
