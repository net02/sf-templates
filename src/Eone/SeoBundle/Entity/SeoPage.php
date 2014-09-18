<?php

namespace Eone\SeoBundle\Entity;

use Sonata\SeoBundle\Seo\SeoPage as BaseSeoPage;
use Eone\SeoBundle\Model\SeoPageInterface;

class SeoPage extends BaseSeoPage implements SeoPageInterface
{
    protected $titlePrefix;

    public function __construct($title = '')
    {
        parent::__construct($title);
        $this->titlePrefix = '';
    }

    public function setTitlePrefix($prefix)
    {
        $this->titlePrefix = $prefix;

        return $this;
    }

    public function getTitlePrefix()
    {
        return $this->titlePrefix;
    }

    public function getTitle()
    {
        $titles = array($this->title, $this->titlePrefix);
        
        return implode($this->separator, array_filter($titles));
    }
}
