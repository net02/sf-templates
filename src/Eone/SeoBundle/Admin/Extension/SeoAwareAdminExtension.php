<?php

namespace Eone\SeoBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;

class SeoAwareAdminExtension extends AdminExtension {
    
    public function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('SEO')
                ->add('seo_data', 'eone_seo_data', array(/** @Ignore */'label' => false))
            ->end()
        ;
    }
}
