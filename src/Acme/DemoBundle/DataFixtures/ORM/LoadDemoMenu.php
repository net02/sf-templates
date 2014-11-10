<?php

namespace Acme\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eone\MenuBundle\Entity\MenuNode;

/**
 * LoadDemoMenu
 *
 * @author Daniele Bartocci <net02.it@gmail.com>
 */
class LoadDemoMenu implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        $defaultLocale = array_keys($this->container->getParameter('locales_available'))[0];
                
        $repo = $manager->getRepository('EoneMenuBundle:MenuNode');
        $root = $repo->findRootByAlias('main');
        
        $node = new MenuNode();
        $node
            ->setParent($root)
            ->setName('language switch')
            ->setRoute('switch_demo')
        ;
        $i18n = $node->getTranslationObject();        
        $i18n->setLocale($defaultLocale)->setLabel($node->getName());
        $node->addTranslation($i18n);
        $manager->persist($node);
        
        $node = new MenuNode();
        $node
            ->setParent($root)
            ->setName('homepage')
            ->setRoute('home')
        ;
        $i18n = $node->getTranslationObject();        
        $i18n->setLocale($defaultLocale)->setLabel($node->getName());
        $node->addTranslation($i18n);
        $manager->persist($node);
        $manager->flush();
        $repo->moveUp($node);
        
        $childNode = new MenuNode();
        $childNode
            ->setName('external link')
            ->setUri('http://www.example.com')
            ->setParent($node)
        ;
        $i18n = $childNode->getTranslationObject();        
        $i18n->setLocale($defaultLocale)->setLabel($childNode->getName());
        $childNode->addTranslation($i18n);
        $manager->persist($childNode);
        
        $node = new MenuNode();
        $node
            ->setParent($root)
            ->setName('absolute url')
            ->setRoute('home')
            ->setAbsolute(true)
        ;
        $i18n = $node->getTranslationObject();        
        $i18n->setLocale($defaultLocale)->setLabel($node->getName());
        $node->addTranslation($i18n);
        $manager->persist($node);
        
        $childNode = new MenuNode();
        $childNode
            ->setName('sitemap xml')
            ->setUri('/sitemap.xml')
            ->setParent($node)
        ;
        $i18n = $childNode->getTranslationObject();        
        $i18n->setLocale($defaultLocale)->setLabel($childNode->getName());
        $childNode->addTranslation($i18n);
        $manager->persist($childNode);
        
        $node = new MenuNode();
        $node
            ->setParent($root)
            ->setName('with hash')
            ->setUri('#test-hash')
        ;
        $i18n = $node->getTranslationObject();        
        $i18n->setLocale($defaultLocale)->setLabel($node->getName());
        $node->addTranslation($i18n);
        $manager->persist($node);       
                
        $manager->flush();
    }
}
