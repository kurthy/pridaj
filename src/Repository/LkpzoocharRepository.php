<?php

namespace App\Repository;

use App\Entity\Lkpzoochar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Lkpzoochar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lkpzoochar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lkpzoochar[]    findAll()
 * @method Lkpzoochar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LkpzoocharRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lkpzoochar::class);
    }

    // /**
    //  * @return Lkpzoochar[] Returns an array of Lkpzoochar objects
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
    public function findOneBySomeField($value): ?Lkpzoochar
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
