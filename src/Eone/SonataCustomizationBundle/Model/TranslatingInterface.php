<?php
namespace Eone\SonataCustomizationBundle\Model;
 
use Eone\SonataCustomizationBundle\Model\TranslatableInterface;
 
interface TranslatingInterface
{
    function setLocale($string);
 
    function getLocale();
 
    function setTranslatable(TranslatableInterface $translatable);
 
    /**
     * @return TranslatableInterface
     */
    function getTranslatable();
}
?>