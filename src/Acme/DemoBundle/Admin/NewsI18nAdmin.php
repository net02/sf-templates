<?php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class NewsI18nAdmin extends Admin {
    
    protected $parentAssociationMapping = 'translatable';
    protected $classnameLabel = 'Translations';

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('Content')
                ->add('title', 'textarea')
            ->end()
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('title')
        ;
    }

}
