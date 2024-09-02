<?php

namespace App\Service;

use App\Entity\Sorties;
use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;

class SortieService
{

    public function __construct(private readonly EtatsRepository $etatsRepository, SortiesRepository $sortiesRepository)
    {}


    public function postLoad(Sorties $sorties): void
    {
        $this->etatEnCreation($sorties);
        $this->etatOuverte($sorties);
        $this->etatCloturee($sorties);
        $this->etatActiviteEnCours($sorties);
        $this->etatActiviteTerminee($sorties);
//        $this->etatActiviteHistorisee($sorties);
    }

    public function etatEnCreation(Sorties $sorties): void
    {
        $etat = $this->etatsRepository->findOneBy(['id' => 1]);
        if (!$sorties->getIsPublished()) {
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

        if (new \DateTime("now") == $sorties->getDateDebut()) {
            $sorties->setEtat($etat);
        }
    }

    public function etatActiviteTerminee(Sorties $sorties)
    {
        $etat = $this->etatsRepository->findOneBy(['id' => 5]);

        if (new \DateTime("now") > $sorties->getDateDebut()) {
            $sorties->setEtat($etat);
        }
    }

//    public function etatActiviteHistorisee(Sorties $sorties)
//    {
//        $etat = $this->etatsRepository->findOneBy(['id' => 6]);
//
//        if ((new \DateTime()->add  new \DateTime() > ($sorties->getDateDebut()->strtotime('d-m-Y' ,'+1 month')))) {
//            $sorties->setEtat($etat);
//        }
//    }

}