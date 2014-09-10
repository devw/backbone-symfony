<?php

namespace HighFive\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * HighFive\MainBundle\Entity\Recognition
 *
 * @Assert\Callback({"validateDifferentRecipient"})
 * @Assert\Callback({"validatePoints"})
 * @Assert\Callback({"validateRemainingPoints"})
 * @ORM\Table(name="recognition")
 * @ORM\Entity(readOnly=true)
 */
class Recognition
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
     * @var integer
     *
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=50)
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

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
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $sender;

    /**
     * @var User
     *
     * @Assert\NotBlank(groups="full")
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
     * Set points
     *
     * @param integer $points
     *
     * @return Recognition
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Recognition
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * @param User $sender
     *
     * @return Recognition
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $recipient
     *
     * @return Recognition
     */
    public function setRecipient(User $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Validates that the recipient is not the sender.
     *
     * @param ExecutionContext $context
     */
    public function validateDifferentRecipient(ExecutionContext $context)
    {
        if (null !== $this->sender && $this->sender === $this->recipient) {
            $context->addViolationAtSubPath('recipient', 'You cannot recognize yourself.', array(), null);
        }
    }

    /**
     * Validates that the points are a multiple of 10.
     *
     * @param ExecutionContext $context
     */
    public function validatePoints(ExecutionContext $context)
    {
        if (null === $this->points) {
            return;
        }

        if (0 !== ($this->points % 10)) {
            $context->addViolationAtSubPath('points', 'The number of points should be a multiple of 10.', array(), $this->points);
        }
    }

    /**
     * Validates that the recipient is not the sender.
     *
     * @param ExecutionContext $context
     */
    public function validateRemainingPoints(ExecutionContext $context)
    {
        if (null !== $this->sender && $this->sender->getRemainingPoints() < $this->points) {
            $context->addViolationAtSubPath('points', 'You cannot give more points than the amount you have left.', array(), null);
        }
    }
}
