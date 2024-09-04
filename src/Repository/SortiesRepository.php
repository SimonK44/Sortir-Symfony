<?php

namespace App\Repository;

use App\Entity\Sorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function findSortiePaginer(int $limit, int $offset,int $siteId): Paginator
    {
        $q = $this->createQueryBuilder('s')
            ->andWhere('s.Etat <= 6 ')
//            ->andWhere('s.site = :siteId ')
//            ->setParameter(':siteId', $siteId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('s.id', 'ASC')
            ->getQuery();


        return new Paginator($q);
    }

    public function findSortiePaginerAvecFiltre(int $limit, int $offset,int $siteId,array $filtre): Paginator
    {
        $q = $this->createQueryBuilder('s')
            ->andWhere('s.Etat < 6 ')
            ->andWhere('s.site = :siteId ')
            ->setParameter(':siteId', $siteId)
            ->setMaxResults($limit)
            ->setFirstResult($offset);

//Ajout du filtre sur le nom
        if ($filtre['nom']) {
            $q->andWhere('s.nom LIKE :nom')
              ->setParameter(':nom', '%'.$filtre['nom'].'%');
        }

//Ajout du filtre sur la date de début
        if ($filtre['dateDebut']) {
            $q->andWhere('s.dateDebut >= :dateDebut')
              ->setParameter(':dateDebut', $filtre['dateDebut']);
        }

//Ajout du filtre sur la date de fin
        if ($filtre['dateFin']) {
            $q->andWhere('s.dateDebut <= :dateFin')
                ->setParameter(':dateFin', $filtre['dateFin']);
        }

//Ajout du filtre sur les sorties organisees par la personne connectée
        if ($filtre['CheckOrga']) {
            $q->andWhere('s.user = :user')
              ->setParameter(':user', $filtre['user']);
        }

//Ajout du filtre sur les sorties où la personne connectée est inscrites
        if ($filtre['CheckInscript']) {
            $q->leftJoin('s.users', 'u')
              ->andWhere('u.id = :users')
              ->setParameter(':users', $filtre['user']);
        }

//Ajout du filtre sur les sorties où la personne connectée n'est pas inscrites
            if ($filtre['CheckPasInscript']) {
                var_dump($filtre['user']);
                $q->leftJoin('s.users', 'u')
                  ->andWhere('u.id is null');
            }

//Ajout du filtre sur les sorties passees
        if ($filtre['CheckPasse']) {
            $q->andWhere('s.dateDebut <> :dateDebut')
              ->setParameter(':dateDebut', new \DateTime());
        }

// on fait tourner la requete
                $q->getQuery();

        return new Paginator($q);
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
