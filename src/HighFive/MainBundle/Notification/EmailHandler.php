<?php

namespace HighFive\MainBundle\Notification;

use HighFive\MainBundle\Entity\User;
use HighFive\MainBundle\Mailer\MailerInterface;

/**
 * Handler sending notifications per mail.
 */
class EmailHandler implements HandlerInterface
{
    private $mailer;
    private $senderAddress;

    public function __construct(MailerInterface $mailer, $senderAddress)
    {
        $this->mailer = $mailer;
        $this->senderAddress = $senderAddress;
    }

    /**
     * {@inheritDoc}
     */
    public function send($type, User $recipient, $data, User $sender = null)
    {
        switch ($type) {
            case NotificationManager::TYPE_REPLY:
                $template = 'MainBundle:Mail/Notification:reply.html.twig';
                /** @var $data \HighFive\MainBundle\Entity\Message */
                $parameters = array('message' => $data);
                break;

            case NotificationManager::TYPE_RECEIVE_POINTS:
                $template = 'MainBundle:Mail/Notification:recognition.html.twig';
                /** @var $data \HighFive\MainBundle\Entity\Recognition */
                $parameters = array('recognition' => $data);
                break;

            default:
                return;
        }

        $this->mailer->send($recipient->getEmail(), $template, $parameters, $this->senderAddress);
    }
}
