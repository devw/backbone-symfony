<?php

namespace HighFive\MainBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InvitationRepository
 */
class InvitationRepository extends EntityRepository
{
    /**
     * @param string $token
     *
     * @return \HighFive\MainBundle\Entity\Invitation
     */
    public function findOneByToken($token)
    {
        $qb = $this->createQueryBuilder('i')
            ->addSelect('o', 'r')
            ->join('i.organization', 'o')
            ->join('i.recipient', 'r')
            ->where('i.confirmedAt IS NULL')
            ->andWhere('i.token = :token')
            ->setParameter('token', $token)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
