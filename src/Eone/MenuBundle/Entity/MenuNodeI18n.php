<?php

namespace Eone\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eone\SonataCustomizationBundle\Model\Translating;
use Eone\SonataCustomizationBundle\Model\TranslatableInterface;
use Eone\SeoBundle\Model\SeoAwareInterface;

/**
 * Eone\MenuBundle\Entity\MenuNodeI18n
 *
 * @ORM\Table(name="menu_node_i18n")
 * @ORM\Entity
 */
class MenuNodeI18n extends Translating implements SeoAwareInterface {

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
     * @ORM\ManyToOne(targetEntity="MenuNode", inversedBy="translations")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    protected $translatable;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string")
     */
    private $label;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;
    
    /**
     * Translation-dependent route parameters (i.e.: slug).
     * Will override already existing routeParams
     * 
     * @var array $translatedParams
     * @ORM\Column(name="route_params", type="json_array", nullable=true)
     */
    private $translatedParams;
    
    /**
     * @ORM\Column(type="object", nullable=true)
     */
    protected $seo_data;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $title
     * @return MenuNodeI18n
     */
    public function setLabel($label) {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return MenuNodeI18n
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
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
     * Set translatable
     *
     * @param \Eone\MenuBundle\Entity\MenuNode $translatable
     * @return MenuNodeI18n
     */
    public function setTranslatable(TranslatableInterface $translatable = null)
    {
        $this->translatable = $translatable;
    
        return $this;
    }

    /**
     * Get translatable
     *
     * @return \Eone\MenuBundle\Entity\MenuNode 
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return MenuNodeI18n
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set translatedParams
     *
     * @param array $translatedParams
     * @return MenuNodeI18n
     */
    public function setTranslatedParams(array $translatedParams)
    {
        $this->translatedParams = $translatedParams;
    
        return $this;
    }

    /**
     * Get translatedParams
     *
     * @return array 
     */
    public function getTranslatedParams()
    {
        return $this->translatedParams;
    }
    
    public function getSeoData() {
        return $this->seo_data;
    }
    
    public function setSeoData($data) {
        $this->seo_data = $data;
    }
}