<?php

namespace App\EntityListener;

use App\Entity\Sorties;
use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;
use DateTime;

class SortieListener
{
    public function __construct(EtatsRepository $etatsRepository, SortiesRepository $sortiesRepository) {
        $this->sortiesRepository = $sortiesRepository;
        $this->etatsRepository = $etatsRepository;
    }

    public function postLoad(sorties $sortie)
    {

        $now = new DateTime("now");
        $unMoisAuparavant = new DateTime("now");
        $unMoisAuparavant->modify('-1 month');

        $etatEnCreation = $this->etatsRepository->findOneBy(['id' => 1]);
        $etatOuverte = $this->etatsRepository->findOneBy(['id' => 2]);
        $etatCloturee = $this->etatsRepository->findOneBy(['id' => 3]);
        $etatActiviteEnCours = $this->etatsRepository->findOneBy(['id' => 4]);
        $etatActiviteTerminee = $this->etatsRepository->findOneBy(['id' => 5]);
        $etatActiviteHistorisee = $this->etatsRepository->findOneBy(['id' => 6]);

        $debutSortiePlusDuree = clone $sortie->getDateDebut();
        $debutSortiePlusDuree->modify("+{$sortie->getDuree()} minutes");

        //Etat en création
        if (!$sortie->getIsPublished()) {
            return $sortie->setEtat($etatEnCreation);
        }

        //Etat activitée historisée
        if ($unMoisAuparavant > ($sortie->getDateDebut())) {
            return $sortie->setEtat($etatActiviteHistorisee);
        }

        //Etat activitée terminée
        if ($now > $debutSortiePlusDuree) {
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
    }

}