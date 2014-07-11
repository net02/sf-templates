<?php

namespace Eone\BlockBundle\Twig\Extension;

use Sonata\BlockBundle\Templating\Helper\BlockHelper;
use Symfony\Component\HttpFoundation\RequestStack;

class BlockExtension extends \Twig_Extension
{    
    protected $eventName;
 
    /**
     * @var BlockHelper 
     */
    protected $blockHelper;
    
    /**
     * @var RequestStack 
     */
    private $requestStack;

    public function __construct($event, BlockHelper $blockHelper, RequestStack $stack)
    {
        $this->eventName = $event;
        $this->blockHelper = $blockHelper;
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
            new \Twig_SimpleFunction('eone_block', array($this, 'getBlockEvent'), array('is_safe' => array('html')))
        );
    }

    public function getBlockEvent($alias, $content = null, array $params = array())
    {
        // we strip excessign whitespaces/newlines since it's feeding a html editor
        $options = array_merge($params, ['alias' => $alias, 'content' => preg_replace('/\s+/', ' ', $content), 'request' => $this->requestStack->getCurrentRequest()]);
        return $this->blockHelper->renderEvent($this->eventName, $options);
    }

    public function getName()
    {
        return 'eone_block';
    }
}
