<?php

namespace App\Service;

use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;
use DateTime;

class SortieService
{
    public function __construct(EtatsRepository $etatsRepository, SortiesRepository $sortiesRepository) {
        $this->sortiesRepository = $sortiesRepository;
        $this->etatsRepository = $etatsRepository;
    }

    public function updateEtatSortie()
    {
        $now = new DateTime("now");
        $unMoisAuparavant = new DateTime("now");
        $unMoisAuparavant->modify('-1 month');

        $sorties = $this->sortiesRepository->findAll();

        $etatEnCreation = $this->etatsRepository->find(1);
        $etatOuverte = $this->etatsRepository->find(2);
        $etatCloturee = $this->etatsRepository->find(3);
        $etatActiviteEnCours = $this->etatsRepository->find(4);
        $etatActiviteTerminee = $this->etatsRepository->find(5);
        $etatActiviteHistorisee = $this->etatsRepository->find(6);

        foreach ($sorties as $sortie) {
            $debutSortiePlusDuree = clone $sortie->getDateDebut();
            $debutSortiePlusDuree->modify("+{$sortie->getDuree()} minutes");

            //Etat activitée historisée
            if ($unMoisAuparavant > ($sortie->getDateDebut())) {
                return $sortie->setEtat($etatActiviteHistorisee);
            }

            //Etat activitée terminée
            if ($now > $sortie->getDateDebut()) {
                return $sortie->setEtat($etatActiviteTerminee);
            }

            //Etat activitée en cours
            if ($sortie->getDateDebut() < $now && $debutSortiePlusDuree > $now && $sortie->getDateDebut() > $unMoisAuparavant) {
                return $sortie->setEtat($etatActiviteEnCours);
            }

            //Etat clôturée
            if (($sortie->getDateCloture() < $now && $sortie->getDateDebut() > $now) || count($sortie->getUsers()) == $sortie->getNbInscriptionsMax()) {
                return $sortie->setEtat($etatCloturee);
            }

            //Etat ouverte
            if (($sortie->getDateCloture() < $now || count($sortie->getUsers()) < $sortie->getNbInscriptionsMax()) && ($sortie->getIsPublished())) {
                return $sortie->setEtat($etatOuverte);
            }

            //Etat en création
            if (!$sortie->getIsPublished()) {
                return $sortie->setEtat($etatEnCreation);
            }
        }
    }
}
