<?php

namespace ApiBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllUsers($search = '')
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if ($search) {
            $queryBuilder->andWhere('u.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $queryBuilder;
    }
}
