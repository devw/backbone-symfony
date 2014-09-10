<?php

namespace HighFive\MainBundle\Event;

use HighFive\MainBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class InvitationEvent extends Event
{
    private $referrer;
    private $registeredEmails;
    private $invitedEmails;
    private $resentEmails;
    private $sentEmails;

    public function __construct(User $referrer, array $registeredEmails, array $invitedEmails, array $resentEmails, array $sentEmails)
    {
        $this->referrer = $referrer;
        $this->registeredEmails = $registeredEmails;
        $this->invitedEmails = $invitedEmails;
        $this->resentEmails = $resentEmails;
        $this->sentEmails = $sentEmails;
    }

    public function getInvitedEmails()
    {
        return $this->invitedEmails;
    }

    public function getRegisteredEmails()
    {
        return $this->registeredEmails;
    }

    public function getReferrer()
    {
        return $this->referrer;
    }

    public function getResentEmails()
    {
        return $this->resentEmails;
    }

    public function getSentEmails()
    {
        return $this->sentEmails;
    }
}
