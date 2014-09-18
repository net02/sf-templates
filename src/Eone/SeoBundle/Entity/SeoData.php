<?php

namespace Eone\SeoBundle\Entity;

class SeoData {

    /**
     * @var  string
     */
    private $metaDescription;

    /**
     * @var string
     */
    private $metaKeywords;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $extraProperties = array();

    /**
     * @var string
     */
    private $set_id;
    

    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setExtraProperties(array $extraProperties)
    {
        $this->extraProperties = $extraProperties;

        return $this;
    }

    public function getExtraProperties()
    {
        return $this->extraProperties;
    }

    public function setSetId($set_id)
    {
        $this->set_id = $set_id;

        return $this;
    }

    public function getSetId()
    {
        return $this->set_id;
    }
}