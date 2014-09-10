<?php

namespace HighFive\MainBundle\Doctrine\Repository;

use HighFive\MainBundle\Entity\Message;
use HighFive\MainBundle\Entity\Organization;
use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 */
class MessageRepository extends EntityRepository
{
    public function getMessagesByOrganization(Organization $organization)
    {
        $qb = $this->createQueryBuilder('m')
            ->addSelect('r', 'rep', 'rep_r', 'rep_rep')
            ->leftJoin('m.recognition', 'r')
            ->leftJoin('m.replies', 'rep')
            ->leftJoin('rep.recognition', 'rep_r')
            ->leftJoin('rep.replies', 'rep_rep') // TODO try to get rid of this join which is always empty but is loaded by JMSSerializerBundle otherwise
            ->where('IDENTITY(m.organization) = :organization')
            ->andWhere('m.parent IS NULL')
            ->setParameter('organization', $organization->getId(), \PDO::PARAM_INT);

        return $qb->getQuery()->getResult();
    }

    public function getReplies(Message $message)
    {
        $qb = $this->createQueryBuilder('m')
            ->addSelect('r', 'rep')
            ->leftJoin('m.recognition', 'r')
            ->leftJoin('m.replies', 'rep') // TODO try to get rid of this join which is always empty but is loaded by JMSSerializerBundle otherwise
            ->where('IDENTITY(m.parent) = :parent')
            ->setParameter('parent', $message->getId(), \PDO::PARAM_INT);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Organization $organization
     * @param integer      $limit
     *
     * @return Message[]
     */
    public function getTopMessages(Organization $organization, $limit = 5)
    {
        $dql = <<<DQL
SELECT m.id, ((CASE WHEN m.recognition IS NULL THEN 0 ELSE m_rec.points END) + IFNULL(SUM(r_rec.points), 0) + 5 * SIZE(m.replies)) AS HIDDEN score
FROM MainBundle:Message m
LEFT JOIN m.recognition m_rec
LEFT JOIN m.replies r
LEFT JOIN r.recognition r_rec
WHERE m.parent IS NULL
AND IDENTITY(m.organization) = :organization
AND m.createdAt >= :date
GROUP BY m
ORDER BY score DESC, m.createdAt DESC
DQL;

        $q = $this->getEntityManager()->createQuery($dql)
            ->setMaxResults($limit)
            ->setParameter('organization', $organization->getId())
            ->setParameter('date', new \DateTime('-7days'));

        $results = $q->getScalarResult();

        $ids = array();
        foreach ($results as $result) {
            $ids[] = $result['id'];
        }

        // We need to do a second query to be able to load the related collection as the original query use GROUP BY.
        $qb = $this->createQueryBuilder('m')
            ->addSelect('r')
            ->leftJoin('m.replies', 'r')
            ->where('m.id IN(:ids)')
            ->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }
}
