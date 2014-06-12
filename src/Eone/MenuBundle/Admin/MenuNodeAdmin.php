<?php

namespace Eone\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MenuNodeAdmin extends Admin {
    protected $datagridValues = array(
        '_sort_by' => 'position'
    );
    protected $maxPerPage = 200;

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->add('name', null, array('max_length' => 64))
            ->add('parent')
            ->add('position')
            ->add('uri', null, array(), array('help' => 'Relative or absolute url'))
            ->add('route', null, array(), array('help' => 'If set, <strong>uri</strong> is ignored'))
            ->add('absolute', null, array('label' => 'Generate absolute url from Route'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->addIdentifier('name', 'string', array('template' => 'EoneMenuBundle:Admin:list_padded.html.twig', 'sortable' => false));
    }
}
