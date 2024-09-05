<?php

namespace App\Controller;

use App\Repository\UserRepository;
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
}
