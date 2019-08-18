<?php

namespace App\Repository;

use App\Entity\Lkppristupnost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Lkppristupnost|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lkppristupnost|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lkppristupnost[]    findAll()
 * @method Lkppristupnost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LkppristupnostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lkppristupnost::class);
    }

    // /**
    //  * @return Lkppristupnost[] Returns an array of Lkppristupnost objects
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
    public function findOneBySomeField($value): ?Lkppristupnost
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
