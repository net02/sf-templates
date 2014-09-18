<?php

namespace Eone\SeoBundle\Twig\Extension;

use Eone\SeoBundle\Model\SeoPageInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Eone\SeoBundle\Event\SeoEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class SeoExtension extends \Twig_Extension
{
    /**
     * @var SeoPageInterface 
     */
    protected $page;
    
    /**
     * @var EventDispatcherInterface 
     */
    protected $dispatcher;
    
    /**
     * @var RequestStack 
     */
    private $requestStack;

    /**
     * @param \Eone\SeoBundle\Model\SeoPageInterface $page
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function __construct(SeoPageInterface $page, EventDispatcherInterface $dispatcher, RequestStack $stack)
    {
        $this->page = $page;
        $this->dispatcher = $dispatcher;
        $this->requestStack = $stack;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('eone_seo_title', array($this, 'getTitle'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('eone_seo_metadata', array($this, 'getMetadata'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'eone_seo';
    }
    
    /**
     * @return string
     */
    public function getTitle($context = array(), $scope = null)
    {
        if (!is_array($context)) {
            $context = array($context);
        }
        $this->dispatcher->dispatch('seo.title', new SeoEvent($this->page, $context, $scope));
        return strip_tags($this->page->getTitle());
    }
    
    /**
     * @return string
     */
    public function getMetadata($context = array(), $scope = null)
    {
        $html = '';        
        if ($request = $this->requestStack->getCurrentRequest()) {
            $this->page->addMeta('property', 'og:url', $request->getUri());
            $this->page->setLinkCanonical($request->getUri());
        }
        
        if (!is_array($context)) {
            $context = array($context);
        }
        $this->dispatcher->dispatch('seo.metadata', new SeoEvent($this->page, $context, $scope));
        
        foreach ($this->page->getMetas() as $type => $metas) {
            foreach ((array) $metas as $name => $meta) {
                list($content, $extras) = $meta;

                $html .= sprintf("<meta %s=\"%s\" content=\"%s\" />\n",
                    $type,
                    $this->normalize($name),
                    $this->normalize($content)
                );

            }
        }
        if ($this->page->getLinkCanonical()) {
            $html .= sprintf("<link rel=\"canonical\" href=\"%s\"/>\n", $this->page->getLinkCanonical());
        }
        
        return $html;
    }

    /**
     * @param string $string
     * @return mixed
     */
    private function normalize($string)
    {
        return htmlentities(strip_tags($string), ENT_COMPAT);
    }
}
