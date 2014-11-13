<?php

namespace Eone\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class MenuNodeAdmin extends Admin
{
    protected $maxPerPage = 200;
    
    /**
     * Contains a list of services tagged as knp_menu.menu
     * @var array
     */
    protected $availableMenus;
    
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    protected function configureListFields(ListMapper $listMapper) {
        $menus = $this->getAvailableMenus();
        if (count($menus) > 1) {            
            $listMapper->add('root', 'choice', array('label' => 'Menu alias', 'sortable' => false, 'choices' => $menus));
        }
        
        $listMapper
            ->addIdentifier('name', 'string', array('template' => 'EoneMenuBundle:Admin:list_padded.html.twig', 'sortable' => false))
            ->add('path', 'string', array('template' => 'EoneMenuBundle:Admin:list_target.html.twig', 'sortable' => false))
            ->add('arrows', 'string', array('template' => 'EoneMenuBundle:Admin:list_tree_arrows.html.twig', 'label' => 'Sort'));        
    }

    protected function configureFormFields(FormMapper $formMapper) {
        $subject = $this->getSubject();
        
        $formMapper->add('name', null, array('max_length' => 64));
        
        $formMapper
            ->add('parent', 'eone_sonata_tree', array(
                'class'         => $this->getClass(),
                'required'      => true,
                'query_builder' => function($repo) use ($subject) {
                    $qb = $repo->createQueryBuilder('m');
                    if ($subject->getId()) {
                        $qb
                        ->where('m.id <> :id')
                        ->setParameter('id', $subject->getId())
                        ;
                        $children = $repo->getChildren($subject);
                        if ($children) {
                            $qb
                            ->andWhere('m.id NOT IN (:children)')
                            ->setParameter('children', $children);
                        }
                    }

                    $qb->orderBy('m.root, m.lft', 'ASC');
                    return $qb;
                }
            ))
            ->end()
            ->with('Target')
                ->add('uri', null, array(), array('help' => 'Relative or absolute url'))
                ->add('route', null, array(), array('help' => 'If set, <strong>uri</strong> is ignored'))
                // hidden by default
//                ->add('routeParams', 'eone_key_value', array('label' => 'Route parameters', 'required' => false))
                ->add('absolute', null, array('label' => 'Generate absolute url from Route', 'required' => false))
            ->end();
    }
    
    /**
     * Sets the default menu to new nodes
     */
    public function getNewInstance() {
        $object = parent::getNewInstance();
        
        $repo = $this->getModelManager()->getEntityManager($this->getClass())->getRepository($this->getClass());
        $menus = $this->getAvailableMenus();
        // this will set the default (or only) menu, null if no menu service is defined
        $object->setParent($repo->findRootByAlias(array_shift($menus)));
        
        return $object;
    }
        
    public function validate(ErrorElement $errorElement, $object) {
        $errorElement->with('name')->assertLength(array('max' => 64))->end();
        
        // at least one between uri / route must be set
        if (strlen($object->getUri()) === 0 && strlen($object->getRoute()) === 0) {
            $errorElement->with('uri')->addViolation('Uri or Route must be set.')->end();
        }
        // validate the route
        else if (strlen($object->getRoute())) {
            try {
                $this->getRouter()->generate($object->getRoute(), $object->getRouteParams() ?: array());
            } 
            catch (RouteNotFoundException $ex) {
                $errorElement->with('route')->addViolation('Route does not exist.')->end();
            }
            catch (MissingMandatoryParametersException $ex) {
                $errorElement->with('route')->addViolation('Some mandatory Route Parameters are missing.')->end();
            }
            catch (InvalidParameterException $ex) {
                $errorElement->with('route')->addViolation('Invalid Route Parameters.')->end();
            }
        }
    }
    
    /**
     * Default sorting (root first, then position)
     * @param type $context
     * @return \Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery
     */
    public function createQuery($context = 'list')
    {
        $queryBuilder = $this->getModelManager()->getEntityManager($this->getClass())->createQueryBuilder();
 
        $queryBuilder->select('n')
            ->from($this->getClass(), 'n')
            ->where('n.parent IS NOT NULL')
            ->orderby('n.root, n.lft');
 
        return new ProxyQuery($queryBuilder);
    }
    
    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('moveUp', $this->getRouterIdParameter().'/moveUp');
        $collection->add('moveDown', $this->getRouterIdParameter().'/moveDown');
    }
    
    /**
     * Disable creation when no menu service is set
     * 
     * @param type $name
     * @param type $object
     * @return boolean
     */
    public function isGranted($name, $object = null)
    {
        if ($name === 'CREATE' && !count($this->getAvailableMenus())) {
            return false;
        }
        
        return parent::isGranted($name, $object);
    }
    
    /**
     * @param array $list
     * @return \Eone\MenuBundle\Admin\MenuNodeAdmin
     */
    public function setAvailableMenus(array $list) {
        $menus = array();
        $repo = $this->getModelManager()->getEntityManager($this->getClass())->getRepository($this->getClass());
        foreach ($list as $alias) {
            $root = $repo->findRootByAlias($alias);
            $menus[$root->getId()] = ucfirst($alias);
        }        
        $this->availableMenus = $menus;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getAvailableMenus() {
        return $this->availableMenus;
    }
    
    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @return \Eone\MenuBundle\Admin\MenuNodeAdmin
     */
    public function setRouter(RouterInterface $router) {
        $this->router = $router;
        return $this;
    }
    
    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter() {
        return $this->router;
    }
}
