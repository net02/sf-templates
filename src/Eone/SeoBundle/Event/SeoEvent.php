<?php

namespace Eone\SeoBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Eone\SeoBundle\Model\SeoPageInterface;

class SeoEvent extends Event {

    /**
     * @var \Eone\SeoBundle\Model\SeoPageInterface
     */
    protected $page;
    
    /**
     * @var array 
     */
    protected $context;
    
    /**
     * @var string
     */
    protected $scope;

    public function __construct(SeoPageInterface $page, $context = array(), $scope = null) {
        $this->page     = $page;
        $this->context  = $context;
        $this->scope    = $scope;
    }

    public function getPage() {
        return $this->page;
    }

    public function getContext() {
        return $this->context;
    }

    public function getScope() {
        return $this->scope;
    }
}