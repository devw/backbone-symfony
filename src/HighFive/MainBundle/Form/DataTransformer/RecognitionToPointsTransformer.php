<?php

namespace HighFive\MainBundle\Form\DataTransformer;

use HighFive\MainBundle\Entity\Recognition;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class RecognitionToPointsTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Recognition) {
            throw new UnexpectedTypeException($value, 'Recognition');
        }
        /** @var $value Recognition */

        return $value->getPoints();
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!is_int($value)) {
            throw new UnexpectedTypeException($value, 'integer');
        }

        $recognition = new Recognition();
        $recognition->setPoints($value);

        return $recognition;
    }
}
