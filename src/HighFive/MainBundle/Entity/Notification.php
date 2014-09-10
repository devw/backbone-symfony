<?php

namespace HighFive\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * HighFive\MainBundle\Entity\Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="HighFive\MainBundle\Doctrine\Repository\NotificationRepository")
 */
class Notification
{
    const RECEIVE_POINTS = 'notification.message.received_points';
    const REPLY = 'notification.message.replied';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var array
     *
     * @ORM\Column(name="parameters", type="json_array")
     */
    private $parameters = array();

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $read = false;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\User")
     * @Serializer\Exclude()
     */
    private $sender;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $recipient;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set parameters
     *
     * @param array $parameters
     *
     * @return Notification
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set read
     *
     * @param boolean $read
     *
     * @return Notification
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * Set sender
     *
     * @param User $sender
     *
     * @return Notification
     */
    public function setSender(User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Gets the sender id.
     *
     * @return integer
     * @Serializer\VirtualProperty()
     */
    public function getSenderId()
    {
        if (null === $this->sender) {
            return null;
        }

        return $this->sender->getId();
    }

    /**
     * Set recipient
     *
     * @param User $recipient
     *
     * @return Notification
     */
    public function setRecipient(User $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient
     *
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Gets the recipient id.
     *
     * @return integer
     * @Serializer\VirtualProperty()
     */
    public function getRecipientId()
    {
        return $this->recipient->getId();
    }
}
