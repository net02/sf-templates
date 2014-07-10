<?php

namespace Eone\SonataCustomizationBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Eone\SonataCustomizationBundle\Admin\TranslatingAdminExtension;

class TranslatingAdminController extends CRUDController
{
    /**
     * 
     * @param \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $query
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws AccessDeniedException
     */
    public function batchActionDelete(ProxyQueryInterface $query)
    {
        if (false === $this->admin->isGranted('DELETE')) {
            throw new AccessDeniedException();
        }

        try {
            foreach ($query->getQuery()->iterate() as $object) {
                $this->admin->delete($object[0]);
            }
            
            $this->addFlash('sonata_flash_success', 'flash_batch_delete_success');
        } catch (ModelManagerException $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
    }
    
    public function copyTranslationAction($id = null)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }
        
        $locales = $this->container->getParameter('locales_available');
        foreach($object->getTranslatable()->getTranslations() as $loc) {
            unset($locales[$loc->getLocale()]);
        }
        
        $form = $this->createFormBuilder(['locales' => array_keys($locales)], ['method' => 'DELETE'])
            ->add('locales', 'choice', array(
                'choices'   => $locales,
                'label'     => 'Copy as translation for the following languages:',
                'required'  => false,
                'multiple'  => true,
                'expanded'  => true,
                'constraints' => [new \Symfony\Component\Validator\Constraints\Count(['min' => 1])]
            ))
            ->getForm();
        $form->handleRequest($this->get('request'));
        
        if ($this->getRestMethod() == 'DELETE' && $form && $form->isValid()) {
            // check the csrf token
            $this->validateCsrfToken('sonata.copy');
            try {
                foreach ($this->admin->getExtensions() as $extension) {
                    if ($extension instanceof TranslatingAdminExtension) {
                        $extension->copyTranslation($this->admin, $object, $form->get('locales')->getData());
                        break;
                    }
                }                
                
                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'ok'));
                }
                $this->addFlash(
                    'sonata_flash_success',
                    "Translation copied successfully."
                );
            } 
            catch (ModelManagerException $e) {
                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'error'));
                }
                $this->addFlash(
                    'sonata_flash_error',
                    sprintf("There was an error while copying %s.", $this->admin->toString($object))
                );
            }
            return new RedirectResponse($this->admin->generateUrl('list'));
        }

        $view = $form->createView();
        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());
        
        return $this->render('EoneSonataCustomizationBundle:CRUD:copy_translation.html.twig', array(
            'object'     => $object,
            'form'       => $view,
            'action'     => 'copy',
            'csrf_token' => $this->getCsrfToken('sonata.copy')
        ));
    }
}
