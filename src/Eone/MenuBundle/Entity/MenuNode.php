<?php

namespace Eone\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Eone\SonataCustomizationBundle\Model\Translatable;

/**
 * Eone\MenuBundle\Entity\MenuNode
 *
 * @ORM\Table(name="menu_node",indexes={@ORM\Index(name="filter_idx", columns={"root"}), @ORM\Index(name="sort_idx", columns={"lft"})})
 * @ORM\Entity(repositoryClass="Eone\MenuBundle\Repository\MenuNodeRepository")
 * @Gedmo\Tree(type="nested")
 */
class MenuNode extends Translatable {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * The menu alias this item belongs
     * @see \Eone\MenuBundle\Menu\MenuBuilder
     * 
     * @var string $root
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="string")
     */
    private $root;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;
    
    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="MenuNode", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="MenuNode", mappedBy="parent", cascade="persist")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;
    
    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;
    
    /**
     * @var string $uri
     * @ORM\Column(name="uri", type="string", nullable=true)
     */
    private $uri;
    
    /**
     * @var string $route
     * @ORM\Column(name="route", type="string", nullable=true)
     */
    private $route;
    
    /**
     * @var array $routeParams
     * @ORM\Column(name="route_params", type="json_array", nullable=true)
     */
    private $routeParams;

    /**
     * @var boolean $absolute
     *
     * @ORM\Column(name="route_absolute", type="boolean")
     */
    private $absolute = false;

    /**
     * @ORM\OneToMany(targetEntity="NewsI18n", mappedBy="translatable", cascade={"persist", "remove"}, orphanRemoval=true) 
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Returns the item label, used for rendering in admin pages
     * 
     * @return string
     */
    public function __toString() {        
        return $this->getName() ?: 'New Node';
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
     * Set lvl
     *
     * @param integer $lvl
     * @return MenuNode
     */
    public function setLvl($lvl) {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl() {
        return $this->lvl;
    }
    
    /**
     * Returns node depth in the menu tree
     * 
     * @return int
     */
    public function getDepth()
    {
        return $this->getLvl();
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return MenuNode
     */
    public function setLft($lft) {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft() {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return MenuNode
     */
    public function setRgt($rgt) {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt() {
        return $this->rgt;
    }

    /**
     * Set parent
     *
     * @param MenuNode $parent
     * @return MenuNode
     */    
    public function setParent(MenuNode $parent = null) {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get Parent
     * 
     * @return MenuNode
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set root
     *
     * @param string $root
     * @return MenuNode
     */
    public function setRoot($root) {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return string 
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * Add children
     *
     * @param MenuNode $children
     * @return MenuNode
     */
    public function addChildren(MenuNode $children) {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param MenuNode $children
     */
    public function removeChildren(MenuNode $children) {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MenuNode
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
     * Set uri
     *
     * @param string $uri
     * @return MenuNode
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

    /**
     * Set route
     *
     * @param string $route
     * @return MenuNode
     */
    public function setRoute($route)
    {
        $this->route = $route;
    
        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set routeParams
     *
     * @param array $routeParams
     * @return MenuNode
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;
    
        return $this;
    }

    /**
     * Get routeParams
     *
     * @return array 
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * Set absolute
     *
     * @param boolean $absolute
     * @return MenuNode
     */
    public function setAbsolute($absolute) {
        $this->absolute = $absolute;

        return $this;
    }

    /**
     * Get absolute
     *
     * @return boolean 
     */
    public function getAbsolute() {
        return $this->absolute;
    }

    public function getTranslationObject() {
        return new MenuNodeI18n();
    }    

    /**
     * @return array
     */
    public function toArray() {        
        $children = array();
        foreach ($this->getChildren() as $child) {
            $children[] = $child->toArray();
        }
        
        return array(
            "name"              => $this->getName(),
            "uri"               => $this->getUri(),
            "route"             => $this->getRoute(),
            "routeParameters"   => $this->getRouteParams(),
            "routeAbsolute"     => $this->getAbsolute(),
            "children"          => $children,
            "extra"             => array("id" => $this->getId())
        );
    }
}
