<?php

namespace Eone\SonataCustomizationBundle\Model;

abstract class Translating implements TranslatingInterface {
    
    abstract function setLocale($string);
    
    abstract function getLocale();
    
    abstract function setTranslatable(TranslatableInterface $translatable);
    
    abstract function getTranslatable();

    public function __toString() {
        return $this->getLocale() ? sprintf('\'%s\' Translation', $this->getLocale()) : 'New Translation';
    }
}
