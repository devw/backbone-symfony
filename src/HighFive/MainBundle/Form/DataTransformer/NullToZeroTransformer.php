<?php

namespace HighFive\MainBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class NullToZeroTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return 0;
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        if (0 === $value || null === $value) {
            return null;
        }

        return $value;
    }
}
