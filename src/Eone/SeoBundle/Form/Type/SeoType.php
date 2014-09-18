<?php

namespace Eone\SeoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

/**
 * A form type for editing the SEO metadata and/or SeoSet.
 */
class SeoType extends AbstractType
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @param \Doctrine\ORM\EntityManager     $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sets = array();
        foreach ($this->em->getRepository('EoneSeoBundle:SeoSet')->findBy(array('is_default' => false)) as $set) {
            $sets[$set->getId()] = $set->getName();
        }
        
        if (count($sets)) {
            $builder->add('set_id', 'choice', array(
                'label'     => 'Utilizza un set di valori',
                'choices'   => $sets,
                'required'  => false,
                'empty_value' => 'Nessuno',
                'help'      => 'I valori specificati qui sotto sovrascriveranno quelli del set.'
            ));
        }
        
        $builder
            ->add('title', 'text', array('label' => 'Titolo'))
            ->add('metaDescription', 'textarea', array('label' => 'Meta Description'))
            ->add('metaKeywords', 'textarea', array('label' => 'Meta Keywords'))
            ->add('extraProperties', 'eone_key_value', array('label' => 'Tag aggiuntivi', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eone\SeoBundle\Entity\SeoData',
            'required' => false,
            'by_reference' => false,
        ));
    }

    public function getName()
    {
        return 'eone_seo_data';
    }
}

