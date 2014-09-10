<?php

namespace HighFive\MainBundle\Event;

use HighFive\MainBundle\Entity\Recognition;
use Symfony\Component\EventDispatcher\Event;

class RecognitionEvent extends Event
{
    private $recognition;

    public function __construct(Recognition $recognition)
    {
        $this->recognition = $recognition;
    }

    public function getRecognition()
    {
        return $this->recognition;
    }
}
