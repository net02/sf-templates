<?php

namespace Acme\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Eone\MenuBundle\Entity\MenuNode;

/**
 * LoadDemoMenu
 *
 * @author Daniele Bartocci <net02.it@gmail.com>
 */
class LoadDemoMenu implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $node = new MenuNode();
        $node
            ->setRoot('main')
            ->setName('node 2')
            ->setUri('#test-hash')
            ->setPosition('2')
        ;
        $manager->persist($node);
        
        $node = new MenuNode();
        $node
            ->setRoot('main')
            ->setName('node 1')
            ->setRoute('menu_demo')
            ->setPosition('1')
        ;
        $manager->persist($node);
        
        $childNode = new MenuNode();
        $childNode
            ->setRoot('main')
            ->setName('node 1.1')
            ->setUri('http://www.example.com')
            ->setPosition('1.1')
            ->setParent($node)
        ;
        $manager->persist($childNode);
        
        $node = new MenuNode();
        $node
            ->setRoot('main')
            ->setName('node 3 abs')
            ->setRoute('menu_demo')
            ->setAbsolute(true)
            ->setPosition('3')
        ;
        $manager->persist($node);        
                
        $manager->flush();
    }
}
