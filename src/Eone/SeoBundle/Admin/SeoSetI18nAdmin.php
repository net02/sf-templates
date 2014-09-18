<?php

namespace Eone\SeoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SeoSetI18nAdmin extends Admin {
    
    protected $parentAssociationMapping = 'translatable';
    protected $classnameLabel = 'Translations';

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('Content')
                ->add('title', 'text', array('label' => 'Titolo', 'required' => false))
                ->add('metaDescription', 'textarea', array('label' => 'Meta Description', 'required' => false))
                ->add('metaKeywords', 'textarea', array('label' => 'Meta Keywords', 'help' => 'Separate da virgole', 'required' => false))
                ->add('extraProperties', 'eone_key_value', array('label' => 'Tag aggiuntivi', 'required' => false))
            ->end()
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('title', null, array('label' => 'Titolo'))
            ->add('metaDescription', null, array('label' => 'Description'))
            ->add('metaKeywords', null, array('label' => 'Keywords'))
        ;
    }

}
