<?php

namespace App\Service;

use App\Entity\Sorties;
use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;

class SortieService
{

    public function __construct(private readonly EtatsRepository $etatsRepository, SortiesRepository $sortiesRepository)
    {}

    public function postLoad(Sorties $sorties): void
    {
        $etat = $this->etatsRepository->findOneBy(['id' => 1]);
        if (!$sorties->isPublished()) {
            $sorties->setEtat($etat);
        }
    }


    public function etatOuverte(Sorties $sorties): void
    {
        $etat = $this->etatsRepository->findOneBy(['id' => 2]);

        if ($sorties->getDateCloture() > new \DateTime() && count($sorties->getUsers()) < $sorties->getNbInscriptionsMax()) {
           $sorties->setEtat($etat);
        }
    }

    public function etatCloturee(Sorties $sorties)
    {
        $etat = $this->etatsRepository->findOneBy(['id' => 3]);

        if (new \DateTime() > $sorties->getDateCloture() && count($sorties->getUsers()) == $sorties->getNbInscriptionsMax()) {
            $sorties->setEtat($etat);
        }
    }

    public function etatActiviteEnCours(Sorties $sorties)
    {
        $etat = $this->etatsRepository->findOneBy(['id' => 4]);

        if (new \DateTime() == $sorties->getDateDebut()) {
            $sorties->setEtat($etat);
        }
    }

    public function etatActiviteTerminee(Sorties $sorties)
    {
        $etat = $this->etatsRepository->findOneBy(['id' => 5]);

        if (new \DateTime() > $sorties->getDateDebut()) {
            $sorties->setEtat($etat);
        }
    }

//    public function etatActiviteHistorisee(Sorties $sorties)
//    {
//        $etat = $this->sortiesRepository->findOneBy(['id' => 6]);
//
//        if ((new \DateTime() > ($sorties->getDateDebut() + 30))) {
//            $sorties->setEtat($etat);
//        }
//    }

}