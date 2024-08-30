<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sorties;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/inscri')]
class InscriptionController extends AbstractController
{
    #[Route('/inscription/{idSortie}/{idUser}', name: '_inscription',requirements: ['idSortie' => '\d+', 'idUser' => '\d+'])]
    public function inscription(Request $request, int $idSortie,int $idUser,  EntityManagerInterface $entityManager,SortiesRepository $sortiesRepository): Response    {

        //insertion du user à la sortie
        $sortiesRepository->InsertUserSortie($idSortie,$idUser);
        // envoie message flash
        $this->addFlash('succes','Inscription effectuée avec succes 😊');

        return $this->redirectToRoute('app_sorties_show', array(
            'id' => $idSortie,
        ));
    }

    #[Route('/desinscription/{idSortie}/{idUser}', name: '_desincription',requirements: ['idSortie' => '\d+', 'idUser' => '\d+'])]
    public function desinscription(Request $request, int $idSortie,int $idUser,  EntityManagerInterface $entityManager,SortiesRepository $sortiesRepository): Response
    {
        // suppression du user de la sortie
        $sortiesRepository->DeleteUserSortie($idSortie,$idUser);
        // envoie message flash
        $this->addFlash('succes','Désinscription effectuée avec succes 😊');

        return $this->redirectToRoute('app_sorties_show', array(
            'id' => $idSortie,
        ));

    }
}
