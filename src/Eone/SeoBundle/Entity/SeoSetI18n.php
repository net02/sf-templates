<?php

namespace Eone\SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eone\SonataCustomizationBundle\Model\Translating;
use Eone\SonataCustomizationBundle\Model\TranslatableInterface;

/**
 * SeoSetI18n
 *
 * @ORM\Table("seo_set_i18n")
 * @ORM\Entity
 */
class SeoSetI18n extends Translating {
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\Column(name="locale", type="string", length=6)
     */
    private $locale;

    /**
     * @ORM\ManyToOne(targetEntity="SeoSet", inversedBy="translations")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    protected $translatable;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="metaDescription", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="metaKeywords", type="text", nullable=true)
     */
    private $metaKeywords;

    /**
     * @var array
     *
     * @ORM\Column(name="extraProperties", type="json_array", nullable=true)
     */
    private $extraProperties;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return SeoSetI18n
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Set translatable
     *
     * @param SeoSet $translatable
     * @return SeoSetI18n
     */
    public function setTranslatable(TranslatableInterface $translatable = null)
    {
        $this->translatable = $translatable;
    
        return $this;
    }

    /**
     * Get translatable
     *
     * @return SeoSet 
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return SeoSetI18n
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return SeoSetI18n
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return SeoSetI18n
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set extraProperties
     *
     * @param array $extraProperties
     * @return SeoSetI18n
     */
    public function setExtraProperties($extraProperties)
    {
        $this->extraProperties = $extraProperties;

        return $this;
    }

    /**
     * Get extraProperties
     *
     * @return array 
     */
    public function getExtraProperties()
    {
        return $this->extraProperties;
    }
}
