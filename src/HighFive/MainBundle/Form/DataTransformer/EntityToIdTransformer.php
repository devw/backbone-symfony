<?php

namespace HighFive\MainBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements DataTransformerInterface
{
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transforms entities into choice keys.
     *
     * @param object $entity A collection of entities, a single entity or NULL
     *
     * @return integer
     */
    public function transform($entity)
    {
        if (null === $entity || '' === $entity) {
            return null;
        }

        if (!is_object($entity)) {
            throw new UnexpectedTypeException($entity, 'object');
        }

        return $entity->getId();
    }

    /**
     * Transforms choice keys into entities.
     *
     * @param integer $key
     *
     * @return object
     */
    public function reverseTransform($key)
    {
        if ('' === $key || null === $key) {
            return null;
        }

        $entity = $this->repository->find($key);

        if (null === $entity) {
            throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $key));
        }

        return $entity;
    }
}
