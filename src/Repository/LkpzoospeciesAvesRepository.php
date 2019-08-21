<?php

namespace App\Repository;

use App\Entity\LkpzoospeciesAves;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LkpzoospeciesAves|null find($id, $lockMode = null, $lockVersion = null)
 * @method LkpzoospeciesAves|null findOneBy(array $criteria, array $orderBy = null)
 * @method LkpzoospeciesAves[]    findAll()
 * @method LkpzoospeciesAves[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LkpzoospeciesAvesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LkpzoospeciesAves::class);
    }

    // /**
    //  * @return LkpzoospeciesAves[] Returns an array of LkpzoospeciesAves objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LkpzoospeciesAves
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
