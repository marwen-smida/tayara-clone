<?php

namespace App\Repository;

use App\Entity\Utilisaleur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisaleur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisaleur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisaleur[]    findAll()
 * @method Utilisaleur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisaleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisaleur::class);
    }

    // /**
    //  * @return Utilisaleur[] Returns an array of Utilisaleur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Utilisaleur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
