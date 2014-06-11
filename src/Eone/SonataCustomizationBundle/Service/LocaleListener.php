<?php
namespace Eone\SonataCustomizationBundle\Service;

use Eone\SonataCustomizationBundle\Service\Locale;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleListener {
    private $locale;

    public function __construct(Locale $locale) {
        $this->locale = $locale;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $this->locale->setLocale($event->getRequest()->getLocale());
    }
}
