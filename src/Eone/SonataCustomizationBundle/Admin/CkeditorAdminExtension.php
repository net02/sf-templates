<?php

namespace Eone\SonataCustomizationBundle\Admin;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Adds browser and upload routes to the Admin
 *
 * @author Kévin Dunglas <kevin@les-tilleuls.coop>
 */
class CkeditorAdminExtension extends AdminExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection)
    {
        $collection->add('ckeditor_browser', 'ckeditor_browser', array(
            '_controller' => 'EoneSonataCustomizationBundle:CkeditorAdmin:browser'
        ));

        $collection->add('ckeditor_upload', 'ckeditor_upload', array(
            '_controller' => 'EoneSonataCustomizationBundle:CkeditorAdmin:upload'
        ));
    }
}
