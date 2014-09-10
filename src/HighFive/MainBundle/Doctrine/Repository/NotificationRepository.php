<?php

namespace HighFive\MainBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use HighFive\MainBundle\Entity\User;

/**
 * NotificationRepository
 */
class NotificationRepository extends EntityRepository
{
    public function getUnreadNotifications(User $recipient)
    {
        $qb = $this->createQueryBuilder('n')
            ->where('IDENTITY(n.recipient) = :recipient')
            ->andWhere('n.read = :state')
            ->setParameter('recipient', $recipient->getId())
            ->setParameter('state', false);

        return $qb->getQuery()->getResult();
    }
}
