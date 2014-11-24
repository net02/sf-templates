<?php

namespace Eone\SeoBundle\Model;

use Sonata\SeoBundle\Seo\SeoPageInterface as BaseSeoPageInterface;

interface SeoPageInterface extends BaseSeoPageInterface {
    /**
     * @param string $title
     *
     * @return SeoPageInterface
     */
    public function setTitlePrefix($prefix);
    
    public function getTitlePrefix();
    
    public function getSeparator();
}
