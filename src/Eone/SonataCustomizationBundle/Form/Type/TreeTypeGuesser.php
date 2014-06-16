<?php

namespace Eone\SonataCustomizationBundle\Form\Type;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;


class TreeTypeGuesser implements FormTypeGuesserInterface
{
    const TREE_ANNOTATION = '\\Gedmo\\Mapping\\Annotation\\Tree';
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var Reader
     */
    protected $reader;
    private $cache;

    public function __construct(EntityManager $em, Reader $reader)
    {
        $this->em = $em;
        $this->reader = $reader;
        $this->cache = array();
    }

    /**
     * @{inherit}
     */
    public function guessType($class, $property)
    {
        if (!$metadata = $this->getMetadata($class)) {
            return;
        }

        if (!$metadata->hasAssociation($property)) {
            return;
        }

        $mapping = $metadata->getAssociationMapping($property);

        $targetMetadata = $this->getMetadata($mapping['targetEntity']);
        $targetClassReflection = $targetMetadata->getReflectionClass();

        $annotationObj = $this->reader->getClassAnnotation($targetClassReflection, self::TREE_ANNOTATION);

        if (empty($annotationObj)) {
            return;
        }

        $multiple = $metadata->isCollectionValuedAssociation($property);

        return new TypeGuess('eone_sonata_tree', array('class' => $mapping['targetEntity'], 'multiple' => $multiple), Guess::VERY_HIGH_CONFIDENCE);
    }

    protected function getMetadata($class)
    {
        if (array_key_exists($class, $this->cache)) {
            return $this->cache[$class];
        }

        $this->cache[$class] = null;
        try {
            return $this->cache[$class] = $this->em->getClassMetadata($class);
        } catch (MappingException $e) {
            // not an entity or mapped super class
        } catch (LegacyMappingException $e) {
            // not an entity or mapped super class, using Doctrine ORM 2.2
        }
    }

    /**
     * @{inherit}
     */
    public function guessRequired($class, $property)
    {

    }

    /**
     * @{inherit}
     */
    public function guessMaxLength($class, $property)
    {

    }

    /**
     * @{inherit}
     */
    public function guessMinLength($class, $property)
    {

    }

    /**
     * @{inherit}
     */
    public function guessPattern($class, $property)
    {

    }

}
