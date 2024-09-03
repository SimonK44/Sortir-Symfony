<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Sites;
use App\Entity\Sorties;
use App\Form\FiltreType;
use App\Form\LieuxType;
use App\Form\SortiesType;
use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;
use App\Repository\UserRepository;
use App\Service\SortieService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sorties')]
class SortiesController extends AbstractController
{
    #[Route('/{page}/list', name: 'app_sorties_index', requirements: ['page' => '\d+'], defaults: ['page' => 1], methods: ['GET','POST'])]
    public function index(Request $request,
                          SortiesRepository $sortiesRepository,
                          UserRepository $userRepository,
                          int $page
    ): Response
    {

        $filtre = [];

        // gestion de la pagination
        $nbByPage = 25;
        $offset = ($page-1) * $nbByPage;
        $nbTotal = $sortiesRepository->count();


// recuperation du site de la personne connectée
        $siteId = $this->getUser()->getSite()->getId();

        $sorties = $sortiesRepository->findSortiePaginer($nbByPage,$offset,$siteId);

        $form = $this->createForm(FiltreType::class, $filtre);
        $form->handleRequest($request);

// Recupération des information des filtres
        $filtre = $form->getData();

        if ($form->isSubmitted() ) {

            $sorties = $sortiesRepository->findSortiePaginerAvecFiltre($nbByPage,$offset,$siteId,$filtre);
        }


        return $this->render('sorties/index.html.twig', [
            'sorties' => $sorties,
            'page' => $page,
            'nbPagesMax' => ceil($nbTotal / $nbByPage),
            'form_filtre' => $form,
        ]);
    }

    #[Route('/new', name: 'app_sorties_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SortieService $sortieService): Response
    {
        $sortie = new Sorties();
        $lieu = new Lieux();
        $sortie->setUser($this->getUser());
        $sortie->setSite($sortie->getUser()->getSite());
        $sortieService->postLoad($sortie);

        $formLieu = $this->createForm(LieuxType::class, $lieu);
        $form = $this->createForm(SortiesType::class, $sortie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie ajoutée !');

            return $this->redirectToRoute('app_sorties_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sorties/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formLieu' => $formLieu,
        ]);
    }

    #[Route('/{id}', name: 'app_sorties_show', methods: ['GET'])]
    public function show(Sorties $sortie): Response
    {
        return $this->render('sorties/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sorties_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sorties $sortie, EntityManagerInterface $entityManager,
                         SortieService $sortieService): Response
    {
        $form = $this->createForm(SortiesType::class, $sortie);
        $form->handleRequest($request);

        $lieu = new Lieux();
        $formLieu = $this->createForm(LieuxType::class, $lieu);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieService->etatOuverte($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie modifiée !');

            return $this->redirectToRoute('app_sorties_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sorties/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formLieu' => $formLieu,
        ]);
    }

    #[Route('/{id}', name: 'app_sorties_delete', methods: ['POST'])]
    public function delete(Request $request, Sorties $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sorties_index', [], Response::HTTP_SEE_OTHER);
    }

}
