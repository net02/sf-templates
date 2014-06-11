<?php

namespace Eone\SonataCustomizationBundle\Model;

use Eone\SonataCustomizationBundle\Service\Locale;

abstract class Translatable implements TranslatableInterface {
    /**
     * current translation
     * @var \Eone\SonataCustomizationBundle\Model\TranslatingInterface 
     */
    protected $translation = null;

    /**
     *
     * @var \Eone\SonataCustomizationBundle\Service\Locale 
     */
    protected $locale = null;
    
    abstract function getTranslationObject();

    /**
     * @param Locale $locale
     */
    public function setLocale(Locale $locale) {
        $this->locale = $locale;
        return $this;
    }

    /**
     *
     * @param TranslatingInterface $translation 
     */
    public function setTranslation(TranslatingInterface $translation) {
        $this->translation = $translation;
    }

    /**
     * get current translation based on Locale object
     * @return boolean 
     */
    public function getTranslation() {
        if (!is_null($this->translation) && $this->translation->getLocale() == $this->locale->getLocale()) {
            return $this->translation;
        }
        $translations = $this->getTranslations();

        if (!$this->locale || count($translations) < 1) {
            return null;
        }

        $defaultLocale = $this->locale->getDefaultLocale();
        $locale = $this->locale->getLocale();

        foreach ($translations as $translation) {
            $translationLocale = $translation->getLocale();

            // translation by default locale that will be used
            // if a translation by locale is not found
            if ($translationLocale == $defaultLocale) {
                $defaultTranslation = $translation;
            }

            if ($translationLocale == $locale) {
                $this->setTranslation($translation);
            }
        }

        if (is_null($this->translation) && isset($defaultTranslation)) {
            $this->setTranslation($defaultTranslation);
        }

        // if even the default translation is not present
        // set the first translation found
        if (is_null($this->translation)) {
            foreach ($translations as $translation) {
                $this->setTranslation($translation);
                break;
            }
        }

        return $this->translation;
    }

    /**
     * Add translations
     *
     * @param TranslatingInterface $translation
     */
    public function addTranslation(TranslatingInterface $translation) {
        $this->translations[$translation->getLocale()] = $translation;
        return $this;
    }

    public function addTranslations(TranslatingInterface $translations) {
        $this->addTranslation($translations);
        return $this;
    }

    public function removeTranslation(TranslatingInterface $translations) {
        $this->translations->removeElement($translations);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTranslations() {
        return $this->translations;
    }

    /**
     * Rebuilds the translations ArrayCollection
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTranslationsByLocale() {
        foreach ($this->translations as $key => $translation) {
            unset($this->translations[$key]);
            $this->addTranslation($translation);
        }

        return $this->translations;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTranslationByLocale($locale) {
        return $this->getTranslationsByLocale()->get($locale);
    }

    /**
     * Magic method override, calling the current translating object ones
     * 
     * @param string $method
     * @param array $args
     * @return mixed 
     */
    public function __call($method, $args) {
        $translation = $this->getTranslation();

        if (is_null($translation)) {
            return null;
        }

        $getters = array();

        $getters[] = $method;
        $getters[] = 'get' . self::camelize($method);
        $getters[] = 'is' . self::camelize($method);

        foreach ($getters as $getter) {
            if (method_exists($this->translation, $getter)) {
                return call_user_func_array(array($this->translation, $getter), $args);
            }
        }

        return null;
    }

    /**
     * Camelize a string
     *
     * @static
     * @param string $property
     * @return string
     */
    public static function camelize($property) {
        return preg_replace_callback('/(^|[_. ])+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $property);
    }
}
