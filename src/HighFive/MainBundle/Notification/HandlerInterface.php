<?php

namespace HighFive\MainBundle\Notification;

use HighFive\MainBundle\Entity\User;

/**
 * Interface implemented by all handlers responsible to send a notification
 */
interface HandlerInterface
{
    /**
     * Sends a notification
     *
     * @param string $type      One of the NotificationManager::TYPE_* constants
     * @param User   $recipient
     * @param mixed  $data
     * @param User   $sender
     */
    public function send($type, User $recipient, $data, User $sender = null);
}
