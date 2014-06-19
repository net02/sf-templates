<?php

namespace Eone\BlockBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Eone\SonataCustomizationBundle\Model\TranslatableInterface;
use Symfony\Component\HttpFoundation\Request;

class BlockRepository extends EntityRepository {
    /**
     * @var \Eone\SonataCustomizationBundle\Service\Locale 
     */
    protected $locale;
    
    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function getLocale() {
        return $this->locale;
    }
    
    /**
     * Retrieves or creates (using config array) an ORM block.
     * 
     * @param array $config
     * @return array
     */
    public function findByConfig(array $config) {
        if (!isset($config['alias'])) {
            return null;
        }
        
        $alias = $config['alias'];
        $block = $this->findOneBy(['alias' => $alias]);
        if (!$block) {
            if (!isset($config['request']) || !$config['request'] instanceof Request) {
                throw new \RuntimeException(sprintf('Cannot create block "%s": missing or invalid request parameter in block declaration.', $alias));
            }
            $request = $config['request'];
            
            $class = $this->getClassName();
            $block = new $class();
            $block->setAlias($alias);
            
            if ($block instanceof TranslatableInterface) {
                $default = $block->getTranslationObject();
                $default->setLocale($request->getLocale());
                
                $block->setLocale($this->getLocale())->addTranslation($default);
            }
            
            $block->setContent($config['content']);
            $block->setUri($request->getPathInfo());
            $block->setName(isset($config['name']) ? $config['name'] : null);
            
            $em = $this->getEntityManager();
            $em->persist($block);
            $em->flush($block);
        }
        
        return $block->getActive() ? $block : null;
    }
}