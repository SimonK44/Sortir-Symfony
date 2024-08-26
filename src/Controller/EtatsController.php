<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Form\EtatsType;
use App\Repository\EtatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/etats')]
class EtatsController extends AbstractController
{
    #[Route('/', name: 'app_etats_index', methods: ['GET'])]
    public function index(EtatsRepository $etatsRepository): Response
    {
        return $this->render('etats/index.html.twig', [
            'etats' => $etatsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etat = new Etats();
        $form = $this->createForm(EtatsType::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etat);
            $entityManager->flush();

            return $this->redirectToRoute('app_etats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etats/new.html.twig', [
            'etat' => $etat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etats_show', methods: ['GET'])]
    public function show(Etats $etat): Response
    {
        return $this->render('etats/show.html.twig', [
            'etat' => $etat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etats $etat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtatsType::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_etats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etats/edit.html.twig', [
            'etat' => $etat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etats_delete', methods: ['POST'])]
    public function delete(Request $request, Etats $etat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($etat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etats_index', [], Response::HTTP_SEE_OTHER);
    }
}
