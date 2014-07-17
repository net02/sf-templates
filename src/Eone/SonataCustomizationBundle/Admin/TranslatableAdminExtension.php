<?php

namespace Eone\SonataCustomizationBundle\Admin;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;

class TranslatableAdminExtension extends AdminExtension {
    
    private $localesAvailable;
    
    /**
     * @param array $localesAvailable
     */
    public function __construct(array $locales) {
        $this->localesAvailable = $locales;
    }
    
    public function alterNewInstance(AdminInterface $admin, $object) {        
        // adding the default language instance
        $defaultLocale = array_keys($this->localesAvailable)[0];
        $default = $object->getTranslationObject();
        
        $default->setLocale($defaultLocale);
        $object->addTranslation($default);
    }
    
    public function configureFormFields(FormMapper $formMapper) {
        $admin = $formMapper->getAdmin();
        
        if (!$admin->id($admin->getSubject())) {
            $formMapper
                ->with('Default translation')
                    ->add(
                        'translations',
                        'sonata_type_collection',
                        array(/** @Ignore */'label' => false, 'type_options' => array('delete' => false), 'btn_add' => false),
                        array('edit' => 'inline')
                    )
                ->end()
            ;
        }
    }
    
    public function configureListFields(ListMapper $listMapper) {
        $current = array();
        if ($listMapper->has("_action")) {
            $current = $listMapper->get("_action")->getOption('actions');
            $listMapper->remove("_action");
        }
        // waiting for PR merge to enable child admin urls in action list
//        if ($translatingAdmin = $this->getTranslatingChildAdmin($listMapper->getAdmin())) {
//            $listMapper
//                ->add('_action', 'actions', array(
//                    'actions' => array_merge(array('edit' => [], 'translate' => ['template' => 'EoneSonataCustomizationBundle:CRUD:list__action_translate.html.twig', 'childAdmin' => $translatingAdmin], 'delete' => []), $current),
//                    'label' => 'Actions'
//                ))
//            ;
//        }
//        else {
            $listMapper
                ->add('_action', 'actions', array(
                    'actions' => array_merge(array('edit' => [], 'delete' => []), $current),
                    'label' => 'Actions'
                ))
            ;
//        }
    }
    
    public function configureTabMenu(AdminInterface $admin, MenuItemInterface $menu, $action, AdminInterface $childAdmin = null) {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }
        
        if ($translatingAdmin = $this->getTranslatingChildAdmin($admin)) {
            $menu->addChild('Translate', array('uri' => $translatingAdmin->generateUrl('list')));
        }
    }
    
    private function getTranslatingChildAdmin(AdminInterface $admin) {
        foreach ($admin->getChildren() as $child) {
            $is_translating = false;
            foreach ($child->getExtensions() as $extension) {
                if ($extension instanceof TranslatingAdminExtension) {
                    $is_translating = true;
                    break;
                }
            }
            
            if ($is_translating) {
                return $child;
            }
        }
        return null;
    }

    /**
     * Adding the reference to default translation object, since child events
     * are not called.
     * 
     * @param AdminInterface $admin
     * @param type $object
     */
    public function prePersist(AdminInterface $admin, $object) {
        foreach ($object->getTranslations() as $t) {
            $t->setTranslatable($object);
        }
    }
}
