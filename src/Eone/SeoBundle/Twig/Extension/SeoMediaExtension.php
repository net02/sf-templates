<?php

namespace Eone\SeoBundle\Twig\Extension;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Eone\SeoBundle\Twig\TokenParser\SeoPathTokenParser;

/**
 * Seo adaptation of sonata-media twig extension
 * 
 * @see \Sonata\MediaBundle\Twig\Extension\MediaExtension
 */
class SeoMediaExtension extends \Twig_Extension
{
    protected $mediaService;

    protected $mediaManager;
    
    /**
     * @var Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $generator;
    
    protected $route;

    /**
     * @param \Sonata\MediaBundle\Provider\Pool $mediaService
     * @param \Sonata\MediaBundle\Model\MediaManagerInterface $mediaManager
     */
    public function __construct(Pool $mediaService, MediaManagerInterface $mediaManager, UrlGeneratorInterface $router, $media_route)
    {
        $this->mediaService = $mediaService;
        $this->mediaManager = $mediaManager;
        $this->generator    = $router;
        $this->route        = $media_route;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(new SeoPathTokenParser($this->getName()),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'eone_seo_media';
    }

    /**
     * @param mixed $media
     *
     * @return null|\Sonata\MediaBundle\Model\MediaInterface
     */
    private function getMedia($media)
    {
        if (!$media instanceof MediaInterface && strlen($media) > 0) {
            $media = $this->mediaManager->findOneBy(array(
                'id' => $media
            ));
        }

        if (!$media instanceof MediaInterface) {
            return false;
        }

        if ($media->getProviderStatus() !== MediaInterface::STATUS_OK) {
            return false;
        }

        return $media;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param string                                   $format
     * @param string                                   $title
     *
     * @return string
     */
    public function seo_path($media = null, $format = 'reference', $title = null)
    {
        $media = $this->getMedia($media);

        if (!$media) {
             return '';
        }
        
        $filename = $media->getName();
        if ($title) {
            $filename = sprintf("%s.%s", $title, $media->getExtension());
        }
        
        return $this->generator->generate($this->route, array(
            'id'        => $media->getId(),
            'format'    => $format,
            'filename'  => $filename
        ));
    }

    /**
     * @return \Sonata\MediaBundle\Provider\Pool
     */
    public function getMediaService()
    {
        return $this->mediaService;
    }
}
