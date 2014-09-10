<?php

namespace HighFive\MainBundle\Notification;

use Doctrine\Common\Persistence\ManagerRegistry;
use HighFive\MainBundle\Entity\Notification;
use HighFive\MainBundle\Entity\User;

/**
 * Handler adding notifications for the web UI.
 */
class WebHandler implements HandlerInterface
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritDoc}
     */
    public function send($type, User $recipient, $data, User $sender = null)
    {
        switch ($type) {
            case NotificationManager::TYPE_REPLY:
                $message = Notification::REPLY;
                /** @var $data \HighFive\MainBundle\Entity\Message */
                $parameters = array(
                    'first_name' => $data->getSender()->getFirstName(),
                    'last_name' => $data->getSender()->getLastName()
                );
                break;

            case NotificationManager::TYPE_RECEIVE_POINTS:
                $message = Notification::RECEIVE_POINTS;
                /** @var $data \HighFive\MainBundle\Entity\Recognition */
                $parameters = array(
                    'first_name' => $data->getSender()->getFirstName(),
                    'last_name' => $data->getSender()->getLastName(),
                    'points' => $data->getPoints()
                );
                break;

            default:
                return;
        }

        $this->createNotification($recipient, $message, $parameters, $sender);
    }

    private function createNotification(User $recipient, $message, array $parameters = array(), User $sender = null)
    {
        $notification = new Notification();
        $notification->setRecipient($recipient)
            ->setMessage($message)
            ->setParameters($parameters)
            ->setSender($sender);

        $this->registry->getManager()->persist($notification);
    }
}
