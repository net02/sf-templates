<?php

namespace Eone\SeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProxyController extends Controller
{
    public function viewAction(Request $request, $format, $id)
    {        
        $media = $this->get('sonata.media.manager.media')->findOneBy(array('id' => $id));        
        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $id));
        }
        
        $provider   = $this->get('sonata.media.pool')->getProvider($media->getProviderName());
        $format = $provider->getFormatName($media, $format);
        
        if ($format != 'reference' && !$provider->getFormat($format)) {
            throw new NotFoundHttpException(sprintf('The image format "%s" is not defined. 
                        Is the format registered in your sonata-media configuration?', $format));
        }
        
        $headers = array(
            'Content-Type' => $media->getContentType(),
        );
        return new BinaryFileResponse(sprintf("%s%s", $this->container->getParameter('kernel.root_dir').'/../web', $provider->generatePublicUrl($media, $format)), 200, $headers);
    }
}