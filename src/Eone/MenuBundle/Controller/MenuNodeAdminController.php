<?php

namespace Eone\MenuBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MenuNodeAdminController extends CRUDController
{
    public function listAction()
    {
        if (!count($this->admin->getAvailableMenus())) {
            $this->addFlash('sonata_flash_error', 'Couldn\'t find any menu service to add a new node to. Please verify your settings.');
        }
                
        return parent::listAction();
    }
    
    public function moveUpAction() {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $node = $this->admin->getObject($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('EoneMenuBundle:MenuNode');

        if ($node->getParent()) {
            $repo->moveUp($node);
        }
        
        return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
    }
    
    public function moveDownAction() {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $node = $this->admin->getObject($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('EoneMenuBundle:MenuNode');

        if ($node->getParent()) {
            $repo->moveDown($node);
        }
        
        return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
    }
}
