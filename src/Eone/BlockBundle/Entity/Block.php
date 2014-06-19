<?php

namespace Eone\BlockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eone\SonataCustomizationBundle\Model\Translatable;

/**
 * Eone\BlockBundle\Entity\Block
 *
 * @ORM\Table(name="block", indexes={@ORM\Index(name="filter_idx", columns={"alias"})}, uniqueConstraints={@ORM\UniqueConstraint(name="alias_unique", columns={"alias"})})
 * @ORM\Entity(repositoryClass="Eone\BlockBundle\Repository\BlockRepository")
 */
class Block extends Translatable {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * The unique alias of the block
     * 
     * @var string $alias
     * @ORM\Column(name="alias", type="string")
     */
    private $alias;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;
    
    /**
     * @var boolean
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;
    
    /**
     * @var string $uri
     * @ORM\Column(name="uri", type="string", nullable=true)
     */
    private $uri;

    /**
     * @ORM\OneToMany(targetEntity="BlockI18n", mappedBy="translatable", cascade={"persist", "remove"}, orphanRemoval=true) 
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Returns the block name, used in admin pages
     * 
     * @return string
     */
    public function __toString() {        
        return $this->getName() ?: $this->getAlias();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Block
     */
    public function setAlias($alias) {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Block
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Block
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
     * Set uri
     *
     * @param string $uri
     * @return Block
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    
        return $this;
    }

    /**
     * Get uri
     *
     * @return string 
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function getTranslationObject() {
        $i18n = new BlockI18n();
        return $i18n->setTranslatable($this);
    }
}
