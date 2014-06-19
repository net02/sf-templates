<?php

namespace Eone\BlockBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class BlockAdmin extends Admin
{
    protected function configureListFields(ListMapper $listMapper) {        
        $listMapper
            ->addIdentifier('name', null, array('template' => 'EoneSonataCustomizationBundle:CRUD:list_tostring.html.twig'))
            ->add('active', null, array('sortable' => false, 'editable' => true))
            ->add('_action', 'actions', array(
                    'actions' => array('view' => ['template' => 'EoneBlockBundle:Admin:list__action_view.html.twig']),
                    'label' => 'Actions'
                ));
    }

    protected function configureFormFields(FormMapper $formMapper) {        
        $formMapper
            ->add('alias', null, array('label' => 'Alias', 'disabled' => true))
            ->add('name')
            ->add('active', null, array('required' => false));
    }
    
    protected function configureRoutes(RouteCollection $collection) {
        $collection
            ->remove('create')
            ->remove('delete');
    }
}
