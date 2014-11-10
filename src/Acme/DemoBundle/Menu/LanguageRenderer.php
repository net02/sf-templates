<?php

namespace Acme\DemoBundle\Menu;

use Knp\Menu\Renderer\Renderer;
use Knp\Menu\ItemInterface;

/**
 * Renders Language Switch menu
 */
class LanguageRenderer extends Renderer
{
    private $defaultOptions = array(
        'depth' => 1,
        'currentAsLink' => true,
        'currentClass' => 'active',
        'compressed' => true,
        'allow_safe_labels' => false,
    );
    
    public function render(ItemInterface $item, array $options = array()) {
        $options = array_merge($this->defaultOptions, $options);

        return $this->renderRow($item, $options);
    }
    
    protected function renderRow(ItemInterface $item, array $options)
    {
        /**
         * Return an empty string if any of the following are true:
         *   a) The menu has no children eligible to be displayed
         *   b) The depth is 0
         *   c) This menu item has been explicitly set to hide its children
         */
        if (!$item->hasChildren() || 0 === $options['depth'] || !$item->getDisplayChildren()) {
            return '';
        }

        $html = $this->format('<div'.$this->renderHtmlAttributes(['class' => 'menu-wrapper']).'>', 'row', $item->getLevel(), $options);
        $html .= $this->renderColumns($item, $options);
        $html .= $this->format('</div>', 'row', $item->getLevel(), $options);

        return $html;
    }
    
    protected function renderColumns(ItemInterface $item, array $options)
    {
        // render children with a depth - 1
        if (null !== $options['depth']) {
            $options['depth'] = $options['depth'] - 1;
        }

        $html = '';
        foreach ($item->getChildren() as $child) {
            $html .= $this->renderItem($child, $options);
        }

        return $html;
    }
    
    protected function renderItem(ItemInterface $item, array $options)
    {
        // if we don't have access or this item is marked to not be shown
        if (!$item->isDisplayed()) {
            return '';
        }

        $class = array_merge((array) $item->getAttribute('class'), ["menu-item"]);
        if ($item->isCurrent()) {
            $class[] = $options['currentClass'];
        }
        
        $attributes = $item->getAttributes();
        if (!empty($class)) {
            $attributes['class'] = implode(' ', $class);
        }
        
        if ($item->getUri() && (!$item->isCurrent() || $options['currentAsLink'])) {
            $attributes['data-href'] = $this->escape($item->getUri());
        }        
        $html = $this->format('<div'.$this->renderHtmlAttributes($attributes).'>', 'col', $item->getLevel(), $options);
        $html .= $this->renderLabel($item, $options);

        // closing li tag
        $html .= $this->format('</div>', 'col', $item->getLevel(), $options);

        return $html;
    }
    
    protected function renderLabel(ItemInterface $item, array $options)
    {
        if ($options['allow_safe_labels'] && $item->getExtra('safe_label', false)) {
            $label = $item->getLabel();
        }
        else {
            $label = $this->escape($item->getLabel());
        }        
        return $this->format(sprintf('<div%s><a href="%s">%s</a></div>', $this->renderHtmlAttributes(array_merge($item->getLabelAttributes(), ['class' => 'text-center'])), $this->escape($item->getUri()), $label), 'link', $item->getLevel(), $options);
    }
    
    /**
     * If $this->renderCompressed is off, this will apply the necessary
     * spacing and line-breaking so that the particular thing being rendered
     * makes up its part in a fully-rendered and spaced menu.
     *
     * @param  string $html The html to render in an (un)formatted way
     * @param  string $type The type [row,col,link] of thing being rendered
     * @param integer $level
     * @param array $options
     * @return string
     */
    protected function format($html, $type, $level, array $options)
    {
        if ($options['compressed']) {
            return $html;
        }

        switch ($type) {
            case 'row':
            case 'link':
                $spacing = $level * 4;
                break;

            case 'col':
                $spacing = $level * 4 - 2;
                break;
        }
        return str_repeat(' ', $spacing).$html."\n";
    }
}
