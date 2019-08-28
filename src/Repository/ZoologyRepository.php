<?php

namespace App\Repository;

use App\Entity\Zoology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Zoology|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zoology|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zoology[]    findAll()
 * @method Zoology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zoology::class);
    }

    // /**
    //  * @return Zoology[] Returns an array of Zoology objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Zoology
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
