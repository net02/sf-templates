<?php

namespace Eone\SonataCustomizationBundle\Admin;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Exception\ModelManagerException;

class TranslatingAdminExtension extends AdminExtension {
    
    private $localesAvailable;
    
    /**
     * @param array $localesAvailable
     */
    public function __construct(array $locales) {
        $this->localesAvailable = $locales;
    }
    
    public function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('Content')
                ->add('locale', 'choice', array('label' => 'Language', 'choices' => $this->localesAvailable))
            ->end()
        ;
    }
    
    public function configureListFields(ListMapper $listMapper) {
        $current = array();
        if ($listMapper->has("_action")) {
            $current = $listMapper->get("_action")->getOption('actions');
            $listMapper->remove("_action");
        }
        
        $listMapper
            ->addIdentifier('locale', 'string')
            ->add('_action', 'actions', array(
                'actions' => array_merge(array('edit' => [], 'delete' => []), $current),
                'label' => 'Actions'
            ))
        ;
    }
    
    public function validate(AdminInterface $admin, ErrorElement $errorElement, $object) {
        $parent = $object->getTranslatable();
        $translations = $parent->getTranslations();
        
        // creazione
        if (!$admin->id($object)) {
            $translations->add($object);
        }
        $locales = array();        
        foreach ($translations as $t) {
            if (in_array($t->getLocale(), $locales)) {
                $errorElement
                    ->with('locale')
                        ->addViolation('Duplicate translation for language ' . $this->localesAvailable[$t->getLocale()])
                    ->end()
                ;
            } else {
                $locales[] = $t->getLocale();
            }
        }
    }
    
    public function preRemove(AdminInterface $admin, $object) {
        $parent = $object->getTranslatable();
        
        // do not delete last translation
        if (count($parent->getTranslations()) == 1) {
            throw new ModelManagerException('You must keep at least one translation');
        }
    }

    public function prePersist(AdminInterface $admin, $object) {
        $parent = $admin->getParent()->getSubject();
        $object->setTranslatable($parent);
    }
}
