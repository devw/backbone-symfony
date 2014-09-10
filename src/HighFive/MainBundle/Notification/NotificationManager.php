<?php

namespace HighFive\MainBundle\Notification;

use HighFive\MainBundle\Entity\User;

/**
 * Manager responsible to send notifications by calling different handlers.
 */
class NotificationManager
{
    const TYPE_RECEIVE_POINTS = 'receive_points';
    const TYPE_REPLY = 'reply';

    /**
     * @var HandlerInterface[]
     */
    private $handlers = array();

    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Sends a notification
     *
     * @param string $type      One of the TYPE_* constants
     * @param User   $recipient
     * @param mixed  $data
     * @param User   $sender
     */
    public function send($type, User $recipient, $data, User $sender = null)
    {
        foreach ($this->handlers as $handler) {
            $handler->send($type, $recipient, $data, $sender);
        }
    }
}
