<?php

namespace Eone\SeoBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SitemapEvent extends Event {

    /**
     * @var array
     */
    protected $urls = array();
    
    /**
     * @var string
     */
    protected $locale;
    
    public function __construct($locale) {
        $this->locale = $locale;
    }
    
    public function getLocale() {
        return $this->locale;
    }

    public function getUrls() {
        return $this->urls;
    }

    public function setUrls(array $urls) {
        $this->urls = $urls;
    }
}