<?php

namespace HighFive\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HighFive\MainBundle\Entity\Invitation
 *
 * @ORM\Table(name="invitation")
 * @ORM\Entity(repositoryClass="HighFive\MainBundle\Doctrine\Repository\InvitationRepository")
 */
class Invitation
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
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resent_at", type="datetime", nullable=true)
     */
    private $resentAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmed_at", type="datetime", nullable=true)
     */
    private $confirmedAt;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\Organization")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organization;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="HighFive\MainBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipient;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referrer;

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
     * Set token
     *
     * @param string $token
     *
     * @return Invitation
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Invitation
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
     * Set resentAt
     *
     * @param \DateTime $resentAt
     *
     * @return Invitation
     */
    public function setResentAt(\DateTime $resentAt = null)
    {
        $this->resentAt = $resentAt;

        return $this;
    }

    /**
     * Get resentAt
     *
     * @return \DateTime
     */
    public function getResentAt()
    {
        return $this->resentAt;
    }

    /**
     * Set confirmedAt
     *
     * @param \DateTime $confirmedAt
     *
     * @return Invitation
     */
    public function setConfirmedAt(\DateTime $confirmedAt = null)
    {
        $this->confirmedAt = $confirmedAt;

        return $this;
    }

    /**
     * Get confirmedAt
     *
     * @return \DateTime
     */
    public function getConfirmedAt()
    {
        return $this->confirmedAt;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     *
     * @return Invitation
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

    /**
     * Sets the recipient.
     *
     * @param User $recipient
     *
     * @return Invitation
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
     * Sets the referrer.
     *
     * @param User $referrer
     *
     * @return Invitation
     */
    public function setReferrer(User $referrer)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * @return User
     */
    public function getReferrer()
    {
        return $this->referrer;
    }
}
