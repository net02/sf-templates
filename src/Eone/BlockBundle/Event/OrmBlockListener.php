<?php

namespace Eone\BlockBundle\Event;

use Doctrine\ORM\EntityRepository;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\BlockInterface;

class OrmBlockListener
{
    /**
     * @var EntityRepository 
     */
    protected $repo;
    /**
     * @var string 
     */
    protected $defaulTemplate;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(EntityRepository $repo, $template = null)
    {
        $this->repo = $repo;
        $this->defaulTemplate = $template;
    }
    
    /**
     * @param  BlockEvent
     *
     * @return BlockInterface
     */
    public function onBlock(BlockEvent $event)
    {
        $alias = $event->getSetting('alias');
        if (!$alias) {
            return;
        }
        
        $template = $event->getSetting('template', $this->defaulTemplate);        
        $config = $event->getSettings();        
        $object = $this->repo->findByConfig($config);
        if (!$object) {
            return;
        }
                
        $block = new Block();
        $block->setId(uniqid());
        $block->setType('sonata.block.service.text');
        $block->setName($alias);
        $block->setSetting('content', $object->getContent());
        if ($template) {
            $block->setSetting('template', $template);
        }
        
        $event->addBlock($block);
    }
}
