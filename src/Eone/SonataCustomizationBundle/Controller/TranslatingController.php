<?php

namespace Eone\SonataCustomizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslatingController extends Controller
{
    public function initialize(Request $request, SecurityContextInterface $security_context) {
        if ($request->get('_route') == '_internal') {
            return;
        }
        
        $params = $request->get('_route_params');
        $locale = $this->get('session')->get('_locale');
        
        if (isset($params['_locale'])) {
            // forced from route
            $locale = $params['_locale'];
            $this->get('session')->set('_locale', $locale);
        }
        else if (!$locale) {
            // from session, with fallback on browser language
            $languages = $this->container->getParameter('locales_available');
            $locale = $request->getPreferredLanguage(array_keys($languages));
            $this->get('session')->set('_locale', $locale); 
        }
        $request->setLocale($locale);
        $this->get('eone.locale')->setLocale($locale);
    }
}