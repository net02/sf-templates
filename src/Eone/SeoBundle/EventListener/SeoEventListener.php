<?php

namespace Eone\SeoBundle\EventListener;

use Eone\SeoBundle\Event\SeoEvent;
use Doctrine\ORM\EntityManager;
use Eone\SeoBundle\Model\SeoPageInterface;

class SeoEventListener
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var boolean
     */
    protected $append;
    
    /**
     * @param \Doctrine\ORM\EntityManager     $em
     */
    public function __construct(EntityManager $em, $append)
    {
        $this->em = $em;
        $this->append = $append;
    }
    
    public function onDefaultSeoTitle(SeoEvent $event) {
        $page = $event->getPage();
        
        if ($this->append) {
            $page->setTitlePrefix($page->getTitle());
            $page->setTitle('');
        }
        
        // Load title from default set (if any)
        $set = $this->em->getRepository('EoneSeoBundle:SeoSet')->findOneBy(array('is_default' => true));
        $this->updateTitleFromObject($page, $set, $this->append);
    }
    
    public function onSeoTitle(SeoEvent $event) {
        $page = $event->getPage();
        
        // Load SeoData from context (if any)
        $context = $event->getContext();
        foreach ($context as $object) {
            if (!$object) {
                continue;
            }
            
            // if we are passed a menu item as context, we'll check its associated menu node entity
            if (in_array('Knp\Menu\ItemInterface', class_implements($object)) && $object->getExtras()['node_id']) {
                $object = $this->em->getRepository('EoneMenuBundle:MenuNode')->find($object->getExtras()['node_id']);
                if ($object) {
                    $page->setTitle($object->getLabel());
                }
            }
            
            $data = $this->getDataFromObject($object);
            if ($data) {
                if ($data->getSetId()) {
                    $set = $this->em->getRepository('EoneSeoBundle:SeoSet')->find($data->getSetId());
                    $this->updateTitleFromObject($page, $set, $this->append);
                }
                
                $this->updateTitleFromObject($page, $data);
            }
        }
    }
    
    public function onDefaultSeoMetadata(SeoEvent $event) {
        $page = $event->getPage();
        
        // Load metadata from default set (if any) and existing values
        $page
            ->addMeta('property', 'og:title', $page->getTitle())
            ->addMeta('property', 'og:type', 'website')
        ;
        $set = $this->em->getRepository('EoneSeoBundle:SeoSet')->findOneBy(array('is_default' => true));
        $this->updateMetadataFromObject($page, $set);
    }
    
    public function onSeoMetadata(SeoEvent $event) {   
        $page = $event->getPage();
        
        // Load SeoData from context (if any)
        $context = $event->getContext();
        foreach ($context as $object) {
            if (!$object) {
                continue;
            }
            
            // if we are passed a menu item as context, we'll check its associated menu node entity
            if (in_array('Knp\Menu\ItemInterface', class_implements($object)) && $object->getExtras()['node_id']) {
                $object = $this->em->getRepository('EoneMenuBundle:MenuNode')->find($object->getExtras()['node_id']);
            }
            
            $data = $this->getDataFromObject($object);
            if ($data) {
                if ($data->getSetId()) {
                    $set = $this->em->getRepository('EoneSeoBundle:SeoSet')->find($data->getSetId());
                    $this->updateMetadataFromObject($page, $set);
                }
                
                $this->updateMetadataFromObject($page, $data);
            }
        }
    }
    
    protected function getDataFromObject($object) {
        if (in_array('Eone\SeoBundle\Model\SeoAwareInterface', class_implements($object))) {
            return $object->getSeoData();
        }
        else if (in_array('Eone\SonataCustomizationBundle\Model\TranslatableInterface', class_implements($object))) {
            $object = $object->getTranslation();
                if (in_array('Eone\SeoBundle\Model\SeoAwareInterface', class_implements($object))) {
                return $object->getSeoData();
            }
        }
        
        return false;
    }
    
    protected function updateTitleFromObject(SeoPageInterface $page, $object, $prefix = false) {
        if ($object && $object->getTitle()) {
            if ($prefix) {
                $page->setTitlePrefix($object->getTitle());
            }
            else {
                $page->setTitle($object->getTitle());
            }
        }
    }
    
    protected function updateMetadataFromObject(SeoPageInterface $page, $object) {
        if ($object) {
            if ($extraProperties = $object->getExtraProperties()) {
                foreach ($extraProperties as $key => $value) {
                    $page->addMeta('property', $key, $value);
                }
            }

            if ($description = $object->getMetaDescription()) {
                $page->addMeta('name', 'description', $description);
                $page->addMeta('property', 'og:description', $description);
            }
            
            if ($keywords = $object->getMetaKeywords()) {
                $page->addMeta('name', 'keywords', $keywords);
            }
        }
    }
}
