<?php

namespace HighFive\MainBundle\Mailer;

use Stampie\Message;

class MutableMessage extends Message
{
    private $subject;
    private $from;

    public function __construct()
    {
        // don't call the parent constructor here to avoid the filtering.
    }

    /**
     * @param string $from
     *
     * @return MutableMessage
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $subject
     *
     * @return MutableMessage
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $to
     *
     * @return MutableMessage
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }
}
