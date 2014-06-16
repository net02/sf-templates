<?php

namespace Eone\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MenuNodeI18nAdmin extends Admin {
    
    protected $parentAssociationMapping = 'translatable';
    protected $classnameLabel = 'Translations';

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('Content')
                ->add('label')
            ->end()
            ->with('Visibility')
                ->add('active', null, array('required' => false))
            ->end()
            // hidden by default
//            ->with('Route parameters')
//                ->add('translatedParams', 'eone_key_value', array('label' => false, 'required' => false))
//            ->end()
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('label')
            ->add('active', null, array('sortable' => false, 'editable' => true))
        ;
    }

}
