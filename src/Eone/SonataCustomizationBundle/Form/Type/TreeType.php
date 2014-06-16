<?php

namespace Eone\SonataCustomizationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TreeType extends AbstractType
{
    protected $options;

    public function __construct(array $options = array())
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults(array(
            'levelPrefix' => '>',
            'orderColumns' => array('root', 'lft'),
            'prefixAttributeName' => 'data-level-prefix',
            'treeLevelField' => 'lvl',
        ));

        $optionsResolver->setAllowedTypes(array(
            'levelPrefix' => 'string',
            'orderColumns' => 'array',
            'prefixAttributeName' => array('string', 'null'),
            'treeLevelField' => 'string',
        ));

        $this->options = $optionsResolver->resolve($options);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $levelPrefix = $this->options['levelPrefix'];

        if (empty($levelPrefix)) {
            return;
        }

        $accessor = PropertyAccess::getPropertyAccessor();

        foreach ($view->vars['choices'] as $choice) {
            $dataObject = $choice->data;
            $level = $accessor->getValue($dataObject, $this->options['treeLevelField']);

            $choice->label = str_repeat($levelPrefix, $level) . ($level ? ' ' : '') . $choice->label;
        }

        $attributeName = $this->options['prefixAttributeName'];
        if (!empty($attributeName)) {
            $view->vars['attr'][$attributeName] = $levelPrefix;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $orderColumns = $this->options['orderColumns'];

        if (!empty($orderColumns)) {

            $queryBuilder = function ($repository) use ($orderColumns) {
                $qb = $repository->createQueryBuilder('a');
                foreach ($orderColumns as $columnName) {
                    $qb->addOrderBy('a.' . $columnName);
                }
                return $qb;
            };

            $resolver->setDefaults(array(
                'query_builder' => $queryBuilder,
            ));
        }

        $resolver->setDefaults(array(
            'expanded' => false,
        ));
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'eone_sonata_tree';
    }
}
