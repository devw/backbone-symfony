<?php

namespace HighFive\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * HighFive\MainBundle\Entity\Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="HighFive\MainBundle\Doctrine\Repository\MessageRepository")
 */
class Message
{
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
     * @Assert\NotBlank()
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var User
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\User")
     * @Serializer\Exclude()
     */
    private $sender;

    /**
     * @var User
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $recipient;

    /**
     * @var Recognition
     *
     * @Assert\NotBlank(groups="recognition")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\Recognition")
     */
    private $recognition;

    /**
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\Message", inversedBy="replies")
     * @ORM\JoinColumn(onDelete="set null")
     * @Serializer\Exclude()
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="HighFive\MainBundle\Entity\Message", mappedBy="parent")
     */
    private $replies;

    /**
     * @var Organization
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\Organization")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $organization;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

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
     * @return Message
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set sender
     *
     * @param User $sender
     *
     * @return Message
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
     * @return Message
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

    /**
     * Set recognition
     *
     * @param Recognition $recognition
     *
     * @return Message
     */
    public function setRecognition(Recognition $recognition = null)
    {
        $this->recognition = $recognition;

        return $this;
    }

    /**
     * Get recognition
     *
     * @return Recognition
     */
    public function getRecognition()
    {
        return $this->recognition;
    }

    /**
     * Set parent
     *
     * @param Message $parent
     *
     * @return Message
     */
    public function setParent(Message $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Message
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get replies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     *
     * @return Message
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
