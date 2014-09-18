<?php

namespace Eone\SeoBundle\Twig\Node;

class SeoPathNode extends \Twig_Node
{
    protected $extensionName;

    /**
     * @param array                 $extensionName
     * @param \Twig_Node_Expression $media
     * @param \Twig_Node_Expression $format
     * @param \Twig_Node_Expression $title
     * @param integer               $lineno
     * @param string                $tag
     */
    public function __construct($extensionName, \Twig_Node_Expression $media, \Twig_Node_Expression $format, \Twig_Node_Expression $title, $lineno, $tag = null)
    {
        $this->extensionName = $extensionName;

        parent::__construct(array('media' => $media, 'format' => $format, 'title' => $title), array(), $lineno, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("echo \$this->env->getExtension('%s')->seo_path(", $this->extensionName))
            ->subcompile($this->getNode('media'))
            ->raw(', ')
            ->subcompile($this->getNode('format'))
        ;
        if ($this->getNode('title')) {
            $compiler
                ->raw(', ')
                ->subcompile($this->getNode('title'))
            ;
        }
        $compiler->raw(");\n");
    }
}