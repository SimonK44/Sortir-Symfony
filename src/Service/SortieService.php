<?php

namespace App\Service;

use App\Entity\Sorties;

class SortieService
{
    public function changementEtat(Sorties $sorties)
    {
        //En création

        //Passage ouvert
        if (date('m d y')->diff($sorties->getDateCloture()) <= 1 && count($sorties->getParticipants()) < $sorties->getNbInscriptionsMax()) {
            $sorties->setEtat(2);
        }
    }

}