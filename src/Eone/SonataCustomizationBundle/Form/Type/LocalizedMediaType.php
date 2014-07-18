<?php

namespace Eone\SonataCustomizationBundle\Form\Type;

use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * LocalizedMediaType
 */
class LocalizedMediaType extends MediaType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->remove('binaryContent');
        $builder->add('binaryContent', 'file', array(
            /** @Ignore */
            'label' => false
        ));
        
        $builder->remove('unlink');
        $builder->add('unlink', 'checkbox', array(
            'attr'     => array('class' => 'unlink-widget'),
            'label_attr' => array('class' => 'unlink-widget '),
            'mapped'   => false,
            'data'     => false,
            'required' => false,
            /** @Ignore */
            'label'    => 'Rimuovi'     // how do i translate this with the right domain?
        ));
    }
    
    public function getName()
    {
        return 'eone_localized_media_type';
    }
}
