<?php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class NewsAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('newsDate', 'date', [
                    'empty_value' => ['month' => 'Month', 'day' => 'Day', 'year' => 'Year'],
                    'format' => 'ddMMMMyyyy'
                ])
                ->add('active', null, array('required' => false));
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('id', null, array('sortable' => false))
                ->addIdentifier('title', 'string', array('template' => 'EoneSonataCustomizationBundle:CRUD:list_translatable.html.twig'))
                ->add('newsDate')
                ->add('active', null, array('sortable' => false, 'editable' => true))
        ;
    }

    public function getNewInstance() {
        $instance = parent::getNewInstance();
        $instance->setActive(true);
        $now = new \DateTime();
        $now->setTime(0, 0, 0);
        $instance->setNewsDate($now);
        return $instance;
    }
}
