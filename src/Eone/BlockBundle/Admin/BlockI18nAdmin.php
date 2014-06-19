<?php

namespace Eone\BlockBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BlockI18nAdmin extends Admin {
    
    protected $parentAssociationMapping = 'translatable';
    protected $classnameLabel = 'Translations';

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('Content')
                ->add('content', 'ckeditor', array('required' => false))
            ->end()
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->add('content', null, array('label' => 'Excerpt', 'template' => 'EoneBlockBundle:Admin:list_excerpt.html.twig'));
    }
}
