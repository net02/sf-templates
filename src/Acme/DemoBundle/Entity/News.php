<?php

namespace Acme\DemoBundle\Entity;

use Eone\SonataCustomizationBundle\Model\Translatable;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity
 */
class News extends Translatable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="NewsI18n", mappedBy="translatable", cascade={"persist", "remove"}, orphanRemoval=true) 
     */
    protected $translations;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_date", type="date", nullable=true)
     */
    private $newsDate;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set newsDate
     *
     * @param \DateTime $date
     * @return News
     */
    public function setNewsDate($newsDate) {
        $this->newsDate = $newsDate;
        return $this;
    }

    /**
     * Get newsDate
     *
     * @return \DateTime 
     */
    public function getNewsDate() {
        return $this->newsDate;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return News
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
     * Constructor
     */
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->getTitle() ? (strlen($this->getTitle()) > 100 ? substr($this->getTitle(), 0, 100) . '...' : $this->getTitle()) : 'New News';
    }

    public function getTranslationObject() {
        $i18n = new NewsI18n();
        return $i18n->setTranslatable($this);
    }
}