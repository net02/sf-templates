<?php

namespace Eone\SeoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SeoSetAdmin extends Admin {
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('name', null, array('label' => 'Nome'))
            ->add('is_default', 'boolean', array('label' => 'Default'))
        ;        
    }

    protected function configureFormFields(FormMapper $formMapper) {
        $subject = $this->getSubject();
        
        $formMapper->add('name', null, array('label' => 'Nome'));
        if (!$subject->getIsDefault()) {
            $formMapper->add('is_default', null, array('label' => 'Utilizza come default per il sito', 'required' => false));
        }
    }
    
    /**
     * Sets the default menu to new nodes
     */
    public function getNewInstance() {
        $object = parent::getNewInstance();
        
        $repo = $this->getModelManager()->getEntityManager($this->getClass())->getRepository($this->getClass());
        if (!count($repo->findAll())) {
            $object->setIsDefault(true);
        }
        
        return $object;
    }
    
    public function prePersist($object) {
        $this->preUpdate($object);
    }
    
    public function preUpdate($object) {
        // ensure only one default is set
        if ($object->getIsDefault()) {
            $em     = $this->getModelManager()->getEntityManager($this->getClass());
            $repo   = $em->getRepository($this->getClass());
            
            $previous = null;
            foreach ($repo->findBy(array("is_default" => true)) as $set) {
                if (!$object->getId() || $set->getId() != $object->getId()) {
                    $previous = $set;
                    break;
                }
            }
            if ($previous) {                
                $previous->setIsDefault(false);
                $em->persist($previous);
                $em->flush();
            }
        }
    }
}
