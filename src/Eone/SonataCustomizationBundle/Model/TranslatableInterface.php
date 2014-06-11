<?php
namespace Eone\SonataCustomizationBundle\Model;
 
use Eone\SonataCustomizationBundle\Model\TranslatingInterface;
use Eone\SonataCustomizationBundle\Service\Locale;
 
interface TranslatableInterface
{    
    function setTranslation(TranslatingInterface $translation);
 
    function addTranslation(TranslatingInterface $translation);
    
    function removeTranslation(TranslatingInterface $translations);
 
    function getTranslations();
    
    /**
     * returns an instance of the translating object
     * @return TranslatingInterface
     */
    function getTranslationObject();
 
    function setLocale(Locale $locale);
}
