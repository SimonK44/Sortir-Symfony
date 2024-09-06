<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users', methods: ['GET'])]
    public function index(Request $request,UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();



        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/{id}', name: 'app_users_delete', methods: ['GET','POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        if ($user->isActif()) {
            $user->setActif(false);
        } else {
            $user->setActif(true);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_users', [], Response::HTTP_SEE_OTHER);
    }


}
