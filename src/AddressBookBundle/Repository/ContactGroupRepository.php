<?php

namespace AddressBookBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContactGroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactGroupRepository extends EntityRepository
{
    public function findByIdAndUserId($groupId, $userId)
    {
        $dql = "SELECT g, u FROM AddressBookBundle:ContactGroup g
                LEFT JOIN g.user u
                WHERE g.id =:id
                AND u.id=:userId";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('id', $groupId)
            ->setParameter('userId', $userId)
            ->getResult();
    }
}
