<?php

namespace Application\Sonata\UserBundle\Controller;

use Sonata\UserBundle\Controller\ResettingFOSUser1Controller;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class ResettingFOSUser1Controller
 *
 * Changes the redirection after password reset, friendly email obfuscate
 *
 * @author Daniele Bartocci <net02.it@gmail.com>
 */
class ResettingFOSUserController extends ResettingFOSUser1Controller
{    
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('sonata_admin_dashboard');
    }
    
    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = substr($email, 0, 1) . '***' . substr($email, $pos-1, 1) . substr($email, $pos);
        }

        return $email;
    }
}