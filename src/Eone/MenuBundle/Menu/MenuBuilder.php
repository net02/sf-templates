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
        
        //todo: localizeit! active-ize it!
        $repo = $this->em->getRepository("EoneMenuBundle:MenuNode");        
        
        foreach($repo->findTopLevelByAlias($alias) as $node) {
            $menu->addChild($this->factory->createFromArray($node->toArray()));
        }
        return $menu;
    }
}
