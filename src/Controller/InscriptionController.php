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
    #[Route('/inscription/{idSortie}', name: '_incription',requirements: ['idSortie' => '\d+'])]
    public function inscription(Request $request, int $idSortie,  EntityManagerInterface $entityManager): Response    {

        return $this->render('inscription/index.html.twig');
    }

    #[Route('/desinscription/{idSortie}/{idUser}', name: '_desincription',requirements: ['idSortie' => '\d+', 'idUser' => '\d+'])]
    public function desinscription(Request $request, int $idSortie,int $idUser,  EntityManagerInterface $entityManager,SortiesRepository $sortiesRepository): Response
    {
        $sortiesRepository->DeleteUserSortie($idSortie,$idUser);


        return $this->redirectToRoute('app_sites_show', array(
            'id' => $idSortie,
        ));



        return $this->render('inscription/index.html.twig');
    }
}
