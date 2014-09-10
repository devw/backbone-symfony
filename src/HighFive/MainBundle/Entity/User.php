<?php

namespace HighFive\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use JMS\SerializerBundle\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * HighFive\MainBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="HighFive\MainBundle\Doctrine\Repository\UserRepository")
 */
class User extends BaseUser
{
    const POINT_LIMIT = 1000;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\MaxLength(255)
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\MaxLength(255)
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $points = 0;

    /**
     * The monthly point balance, denormalized for performance.
     *
     * @var integer
     *
     * @ORM\Column(name="received_points", type="integer")
     */
    private $receivedPoints = 0;

    /**
     * The monthly given point balance, denormalized for performance.
     *
     * @var integer
     *
     * @ORM\Column(name="given_points", type="integer")
     */
    private $givenPoints = 0;

    /**
     * @var Organization
     *
     * @Assert\NotBlank(groups={"Default", "FullRegistration"})
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="HighFive\MainBundle\Entity\Organization")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $organization;

    private $avatarUrl;

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }

    /**
     * Sets the first name.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $this->normalizeName($firstName);

        return $this;
    }

    /**
     * Gets the first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the last name.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $this->normalizeName($lastName);

        return $this;
    }

    /**
     * Gets the last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the number of points of the user.
     *
     * @param integer $points
     *
     * @return User
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Adds some points.
     *
     * @param integer $points
     *
     * @return User
     * @throws \RangeException if the number of points is negative
     */
    public function addPoints($points)
    {
        if ($points < 0) {
            throw new \RangeException(sprintf('The amount of points must be a positive integer, %s given', $points));
        }

        $this->points += $points;
        $this->receivedPoints += $points;

        return $this;
    }

    /**
     * Gets the number of points of the user.
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $receivedPoints
     */
    public function setReceivedPoints($receivedPoints)
    {
        $this->receivedPoints = $receivedPoints;
    }

    /**
     * @return int
     */
    public function getReceivedPoints()
    {
        return $this->receivedPoints;
    }

    /**
     * @param integer $givenPoints
     */
    public function setGivenPoints($givenPoints)
    {
        $this->givenPoints = $givenPoints;
    }

    /**
     * Adds some given points.
     *
     * @param integer $points
     *
     * @return User
     * @throws \RangeException if the number of points is negative or higher than the remaining points
     */
    public function addGivenPoints($points)
    {
        if ($points < 0) {
            throw new \RangeException(sprintf('The amount of used points must be a positive integer, %s given', $points));
        }

        if ($points > $this->getRemainingPoints()) {
            throw new \RangeException(sprintf('The amount of used points must be lower than the number of remaining points, %s given for %s left', $points, $this->getRemainingPoints()));
        }

        $this->givenPoints += $points;

        return $this;
    }

    /**
     * @return integer
     */
    public function getGivenPoints()
    {
        return $this->givenPoints;
    }

    /**
     * @return integer
     * @Serializer\VirtualProperty()
     */
    public function getRemainingPoints()
    {
        return self::POINT_LIMIT - $this->givenPoints;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     *
     * @return User
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

    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    private function normalizeName($string)
    {
        if (null === $string) {
            return null;
        }

        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }
}
