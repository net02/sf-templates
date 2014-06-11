<?php

namespace Eone\SonataCustomizationBundle\Service;

class Locale {
    private $locale,
            $defaultLocale;

    public function __construct($defaultLocale) {
        $this->locale = $defaultLocale;
        $this->defaultLocale = $defaultLocale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function getDefaultLocale() {
        return $this->defaultLocale;
    }
    
    public function __toString() {
        return $this->getLocale();
    }
}
