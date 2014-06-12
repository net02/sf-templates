<?php

namespace Eone\MenuBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MenuNodeRepository extends EntityRepository {

    /**
     * Returns the direct childs of a menu, by alias
     * @see \Eone\MenuBundle\Menu\MenuBuilder
     * 
     * @param string $alias
     * @return array
     */
    public function findTopLevelByAlias($alias) {        
        return $this->findBy(
            ['root' => $alias, 'parent' => null],
            ['position' => 'ASC']
        );
    }
}