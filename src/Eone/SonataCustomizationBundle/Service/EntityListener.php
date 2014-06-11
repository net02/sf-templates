<?php

namespace Eone\SonataCustomizationBundle\Service;

use Eone\SonataCustomizationBundle\Service\Locale;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Eone\SonataCustomizationBundle\Model\TranslatableInterface;

class EntityListener {
    private $locale;

    public function __construct(Locale $locale) {
        $this->locale = $locale;
    }

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof TranslatableInterface) {
            $entity->setLocale($this->locale);
        }
    }
}
