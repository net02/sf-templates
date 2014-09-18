<?php

namespace Eone\SeoBundle\Model;

use Eone\SeoBundle\Entity\SeoData;

/**
 * This interface is responsible to mark a content to be aware of SEO
 */
interface SeoAwareInterface
{
    /**
     * Gets the SEO data for this content.
     *
     * @return SeoData
     */
    public function getSeoData();

    /**
     * Sets the SEO data for this content.
     *
     * @param SeoData $data
     */
    public function setSeoData($data);
}