<?php

namespace Eone\MenuBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Eone\MenuBundle\Entity\MenuNode;

class MenuNodeRepository extends NestedTreeRepository {

    /**
     * Returns the root childs of a menu, by alias, or instantiates it.
     * @see \Eone\MenuBundle\Menu\MenuBuilder
     * 
     * @param string $alias
     * @return array
     */
    public function findRootByAlias($alias) {
        if (!$alias) {
            return null;
        }
        
        $root = $this->findOneBy(['name' => $alias, 'parent' => null]);
        if (!$root) {
            $root = new MenuNode();
            $root->setName($alias);
            
            $em = $this->getEntityManager();
            $em->persist($root);
            $em->flush($root);
        }
        
        return $root;
    }
}