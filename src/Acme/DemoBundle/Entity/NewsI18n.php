<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eone\SonataCustomizationBundle\Model\Translating;
use Eone\SonataCustomizationBundle\Model\TranslatableInterface;
/**
 * NewsI18n
 *
 * @ORM\Table(name="news_i18n")
 * @ORM\Entity
 */
class NewsI18n extends Translating {

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
     * @ORM\ManyToOne(targetEntity="News", inversedBy="translations")
     * @ORM\JoinColumn(name="news_id", referencedColumnName="id")
     */
    protected $translatable;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text")
     */
    private $title;    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return NewsI18n
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get $title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return NewsI18n
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
     * @param \Eone\NewsBundle\Entity\News $translatable
     * @return NewsI18n
     */
    public function setTranslatable(TranslatableInterface $translatable = null)
    {
        $this->translatable = $translatable;
    
        return $this;
    }

    /**
     * Get translatable
     *
     * @return \Eone\NewsBundle\Entity\News 
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }
}