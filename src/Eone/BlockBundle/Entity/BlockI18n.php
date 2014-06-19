<?php

namespace Eone\BlockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eone\SonataCustomizationBundle\Model\Translating;
use Eone\SonataCustomizationBundle\Model\TranslatableInterface;

/**
 * Eone\BlockBundle\Entity\BlockI18n
 *
 * @ORM\Table(name="block_i18n")
 * @ORM\Entity
 */
class BlockI18n extends Translating {

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
     * @ORM\ManyToOne(targetEntity="Block", inversedBy="translations")
     * @ORM\JoinColumn(name="block_id", referencedColumnName="id")
     */
    protected $translatable;
    
    /**
     * @var string $content
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return BlockI18n
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
     * @param \Eone\BlockBundle\Entity\Block $translatable
     * @return BlockI18n
     */
    public function setTranslatable(TranslatableInterface $translatable = null)
    {
        $this->translatable = $translatable;
    
        return $this;
    }

    /**
     * Get translatable
     *
     * @return \Eone\BlockBundle\Entity\Block 
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return BlockI18n
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
}