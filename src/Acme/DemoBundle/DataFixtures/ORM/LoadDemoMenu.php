<?php

namespace Acme\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Eone\MenuBundle\Entity\MenuNode;
use Eone\MenuBundle\Repository\MenuNodeRepository;

/**
 * LoadDemoMenu
 *
 * @author Daniele Bartocci <net02.it@gmail.com>
 */
class LoadDemoMenu implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $repo = $manager->getRepository('EoneMenuBundle:MenuNode');
        $root = $repo->findRootByAlias('main');
        
        $node = new MenuNode();
        $node
            ->setParent($root)
            ->setName('node 2')
            ->setUri('#test-hash')
        ;
        $manager->persist($node);
        
        $node = new MenuNode();
        $node
            ->setParent($root)
            ->setName('node 1')
            ->setRoute('menu_demo')
        ;
        $manager->persist($node);
        $manager->flush();
        $repo->moveUp($node);
        
        $childNode = new MenuNode();
        $childNode
            ->setName('node 1.1')
            ->setUri('http://www.example.com')
            ->setParent($node)
        ;
        $manager->persist($childNode);
        
        $node = new MenuNode();
        $node
            ->setParent($root)
            ->setName('node 3 abs')
            ->setRoute('menu_demo')
            ->setAbsolute(true)
        ;
        $manager->persist($node);        
                
        $manager->flush();
    }
}
