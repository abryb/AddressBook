<?php

namespace AddressBookBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends EntityRepository
{
    public function loadAllAboutContact($id)
    {
        $dql = "SELECT c,a,e,p,g FROM AddressBookBundle:Contact c 
                LEFT JOIN c.addresses a
                LEFT JOIN c.emails e
                LEFT JOIN c.phones p
                LEFT JOIN c.groups g
                WHERE c.id=:id";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('id', $id)->getOneOrNullResult();
    }

    public function findContactsLike($search)
    {
        $dql = "SELECT c FROM AddressBookBundle:Contact c WHERE c.name LIKE :search OR c.surname LIKE :search";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('search', '%'.$search.'%')->getResult();
    }
}
