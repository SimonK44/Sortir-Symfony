<?php

namespace App\Repository;

use App\Entity\Sorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sorties>
 */
class SortiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sorties::class);
    }

    public function InsertUserSortie(int $idSortie, int $idUser): void
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT INTO `user_sorties` (`user_id`, `sorties_id`) VALUES (:idUser, :idSortie )';

        $resultSet = $conn->executeQuery($sql, ['idSortie' => $idSortie, 'idUser' => $idUser]);

    }

    public function DeleteUserSortie(int $idSortie, int $idUser): void
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE FROM user_sorties WHERE sorties_id = :idSortie AND user_id = :idUser';


        $resultSet = $conn->executeQuery($sql, ['idSortie' => $idSortie, 'idUser' => $idUser]);

    }




    //    /**
    //     * @return Sorties[] Returns an array of Sorties objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sorties
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
