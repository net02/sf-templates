<?php

namespace Eone\SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eone\SonataCustomizationBundle\Model\Translatable;

/**
 * SeoSet
 *
 * @ORM\Table("seo_set")
 * @ORM\Entity
 */
class SeoSet extends Translatable {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var boolean $is_default
     *
     * @ORM\Column(name="id_default", type="boolean")
     */
    private $is_default = false;

    /**
     * @ORM\OneToMany(targetEntity="SeoSetI18n", mappedBy="translatable", cascade={"persist", "remove"}, orphanRemoval=true) 
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return SeoSet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set is_default
     *
     * @param boolean $is_default
     * @return SeoSet
     */
    public function setIsDefault($is_default) {
        $this->is_default = $is_default;

        return $this;
    }

    /**
     * Get is_default
     *
     * @return boolean 
     */
    public function getIsDefault() {
        return $this->is_default;
    }

    public function getTranslationObject() {
        $i18n = new SeoSetI18n();
        return $i18n->setTranslatable($this);
    }

    /**
     * Returns the item label, used for rendering in admin pages
     * 
     * @return string
     */
    public function __toString() {        
        return $this->getName() ?: 'New SEO set';
    }
}
