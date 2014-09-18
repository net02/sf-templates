<?php

namespace Eone\SeoBundle\Twig\TokenParser;

use Eone\SeoBundle\Twig\Node\SeoPathNode;

class SeoPathTokenParser extends \Twig_TokenParser
{
    protected $extensionName;

    /**
     * @param string $extensionName
     */
    public function __construct($extensionName)
    {
        $this->extensionName = $extensionName;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $stream = $this->parser->getStream();        
        $media = $this->parser->getExpressionParser()->parseExpression();
        
        $stream->next();
        $format = $this->parser->getExpressionParser()->parseExpression();

        $title = new \Twig_Node_Expression_Constant(null, $this->parser->getCurrentToken()->getLine());
        if (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            $stream->next();
            $title = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new SeoPathNode($this->extensionName, $media, $format, $title, $token->getLine(), $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'seo_path';
    }
}