<?php

namespace HighFive\MainBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class ExplodeLinesTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }

        return implode("\n", $value);
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return array();
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $values = explode("\n", trim($value));

        foreach ($values as &$string) {
            $string = trim($string);
        }

        return array_filter($values);
    }
}
