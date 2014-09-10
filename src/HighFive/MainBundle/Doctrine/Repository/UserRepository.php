<?php

namespace HighFive\MainBundle\Doctrine\Repository;

use HighFive\MainBundle\Entity\Message;
use HighFive\MainBundle\Entity\Organization;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    /**
     * Gets all user who replied to a message
     *
     * @param Message $message
     *
     * @return \HighFive\MainBundle\Entity\User[]
     */
    public function getRepliers(Message $message)
    {
        $dql = <<<DQL
SELECT u
FROM MainBundle:User u
JOIN MainBundle:Message m WITH m.sender = u
WHERE IDENTITY(u.organization) = :organization
AND IDENTITY(m.parent) = :parent
DQL;

        $q = $this->getEntityManager()->createQuery($dql)
            ->setParameter('organization', $message->getOrganization()->getId())
            ->setParameter('parent', $message->getId());

        return $q->getResult();
    }

    /**
     * @param Organization $organization
     * @param int          $limit
     *
     * @return \HighFive\MainBundle\Entity\User[]
     */
    public function getBoard(Organization $organization, $limit = 10)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('IDENTITY(u.organization) = :organization')
            ->andWhere('u.points > 0')
            ->setParameter('organization', $organization->getId())
            ->addOrderBy('u.points', 'desc')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Organization $organization
     * @param int          $limit
     *
     * @return \HighFive\MainBundle\Entity\User[]
     */
    public function getMonthlyBoard(Organization $organization, $limit = 10)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('IDENTITY(u.organization) = :organization')
            ->andWhere('u.receivedPoints > 0')
            ->setParameter('organization', $organization->getId())
            ->addOrderBy('u.receivedPoints', 'desc')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Organization $organization
     * @param int          $limit
     *
     * @return \HighFive\MainBundle\Entity\User[]
     */
    public function getWeeklyBoard(Organization $organization, $limit = 10)
    {
        return $this->getRangeBoard($organization, new \DateTime('-7 days'), $limit);
    }

    /**
     * @param Organization $organization
     * @param \DateTime    $startDate
     * @param int          $limit
     *
     * @return \HighFive\MainBundle\Entity\User[]
     */
    private function getRangeBoard(Organization $organization, \DateTime $startDate, $limit = 10)
    {
        $dql = <<<DQL
SELECT u, SUM(r.points) as HIDDEN p
FROM MainBundle:User u
JOIN MainBundle:Recognition r WITH r.recipient = u
WHERE IDENTITY(u.organization) = :organization
AND r.createdAt >= :date
GROUP BY u
ORDER BY p DESC
DQL;

        $q = $this->getEntityManager()->createQuery($dql)
            ->setMaxResults($limit)
            ->setParameter('organization', $organization->getId())
            ->setParameter('date', $startDate);

        return $q->getResult();
    }
}
