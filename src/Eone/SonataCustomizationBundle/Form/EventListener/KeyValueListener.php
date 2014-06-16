<?php

namespace Eone\SonataCustomizationBundle\Form\EventListener;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Resize a collection form element based on the data sent from the client, keeping keys!
 */
class KeyValueListener extends ResizeFormListener
{
    
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }
        
        // Then add all rows again in the correct order
        $count = 0;
        foreach ($data as $key => $value) {
            $sub = $form->getConfig()->getFormFactory()->createNamed($count, 'form', array('key' => $key, 'value' => $value), array_replace(array(
                'property_path' => '['.$count.']',
                'auto_initialize' => false,
                'label' => false
            ), $this->options));
            $sub->add('key', 'text');
            $sub->add('value', $this->type);
            $form->add($sub);
            
            $count++;
        }
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data || '' === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }
        
        // Remove all empty rows
        if ($this->allowDelete) {
            foreach ($form as $name => $child) {
                if (!isset($data[$name])) {                    
                    $form->remove($name);
                }
            }
        }

        // Add all additional rows
        if ($this->allowAdd) {
            foreach ($data as $name => $value) {
                if (!$form->has($name)) {
                    $sub = $form->getConfig()->getFormFactory()->createNamed($name, 'form', array('key' => $value['key'], 'value' => $value['value']), array_replace(array(
                        'property_path' => '['.$name.']',
                        'auto_initialize' => false,
                        'label' => false
                    ), $this->options));
                    $sub->add('key', 'text');
                    $sub->add('value', $this->type);
                    $form->add($sub);
                }
            }
        }
    }

    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // The data mapper only adds, but does not remove items, so do this
        // here
        if ($this->allowDelete) {
            $toDelete = array();

            foreach ($data as $name => $child) {
                if (!$form->has($name)) {
                    $toDelete[] = $name;
                }
            }

            foreach ($toDelete as $name) {
                unset($data[$name]);
            }
        }
        
        $mapped_data = array();
        foreach($data as $values) {
            if (isset($values["key"]) && isset($values["value"])) {
                $mapped_data[$values["key"]] = $values["value"];
            }
        }
        
        $event->setData($mapped_data);
    }
}
