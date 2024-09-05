<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Form\LieuxType;
use App\Repository\LieuxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lieux')]
class LieuxController extends AbstractController
{
    #[Route('/', name: 'app_lieux_index', methods: ['GET'])]
    public function index(LieuxRepository $lieuxRepository): Response
    {
        return $this->render('lieux/index.html.twig', [
            'lieux' => $lieuxRepository->findAll(),
        ]);
    }

    #[Route('/details/{id}', name: 'app_lieux_details', methods: ['GET'])]
    public function details(Lieux $lieux, LieuxRepository $lieuxRepository)
    {
        return $this->json([
            'success' => true,
            'rue' => $lieux->getRue(),
            'latitude' => $lieux->getLatitude(),
            'longitude' => $lieux->getLongitude(),
            'ville' => $lieux->getVille()->getNomVille()
        ]);
    }

    #[Route('/new', name: 'app_lieux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $lieu = new Lieux();
        $form = $this->createForm(LieuxType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('succes','Nouveau lieu créé avec succes 😊');

            return new JsonResponse([
                'success' => true,
                'id' => $lieu->getId(),
                'nomLieu' => $lieu->getNomLieu(),
                'rue' => $lieu->getRue(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude(),
                'ville' => $lieu->getVille()->getNomVille(),
            ]);
        }

        return new JsonResponse([
            'success' => false
        ], 400);
    }

    #[Route('/{id}', name: 'app_lieux_show', methods: ['GET'])]
    public function show(Lieux $lieux): Response
    {
        return $this->render('lieux/show.html.twig', [
            'lieux' => $lieux,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lieux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lieux $lieux, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LieuxType::class, $lieux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('succes','Lieu modifié avec succes 😊');

            return $this->redirectToRoute('app_lieux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lieux/edit.html.twig', [
            'lieux' => $lieux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieux_delete', methods: ['POST'])]
    public function delete(Request $request, Lieux $lieux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieux->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lieux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lieux_index', [], Response::HTTP_SEE_OTHER);
    }
}
