<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Sites;
use App\Entity\Sorties;
use App\Form\LieuxType;
use App\Form\SortiesType;
use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;
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
    #[Route('/', name: 'app_sorties_index', methods: ['GET'])]
    public function index(SortiesRepository $sortiesRepository): Response
    {
        return $this->render('sorties/index.html.twig', [
            'sorties' => $sortiesRepository->findAll(),
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
