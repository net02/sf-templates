<?php

namespace Eone\MenuBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Eone\MenuBundle\Form\EventListener\KeyValueListener;

class KeyValueType extends CollectionType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_add'] && $options['prototype']) {
            $prototype = $builder
                ->create($options['prototype_name'], 'form', array_replace(array('label' => false), $options['options']))
                ->add('key', 'text')
                ->add('value', $options['type']);
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        $resizeListener = new KeyValueListener(
            $options['type'],
            $options['options'],
            $options['allow_add'],
            $options['allow_delete']
        );

        $builder->addEventSubscriber($resizeListener);
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $optionsNormalizer = function (\Symfony\Component\OptionsResolver\Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };

        $resolver->setDefaults(array(
            'allow_add'      => true,
            'allow_delete'   => true,
            'prototype'      => true,
            'prototype_name' => '__name__',
            'type'           => 'text',
            'by_reference'   => false,
            'options'        => array(),
        ));

        $resolver->setNormalizers(array(
            'options' => $optionsNormalizer,
        ));
    }
    
    public function getName()
    {
        return 'eone_key_value';
    }
}