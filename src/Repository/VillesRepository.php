<?php

namespace App\Repository;

use App\Entity\Villes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Villes>
 */
class VillesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Villes::class);
    }

    public function findIdVille () {
        $q = $this->createQueryBuilder('s')
            ->andWhere('s.Etat <= 6 ')
//            ->andWhere('s.site = :siteId ')
//            ->setParameter(':siteId', $siteId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('s.id', 'ASC')
            ->getQuery();

    }

    //    /**
    //     * @return Villes[] Returns an array of Villes objects
    //     */
        public function findVille(): array
        {
            return $this->createQueryBuilder('v')
                ->orderBy('v.id', 'ASC')
                ->setMaxResults(30)
                ->getQuery()
                ->getResult()
            ;
        }

    //    public function findOneBySomeField($value): ?Villes
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
