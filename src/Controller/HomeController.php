<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HttpClientInterface $htppClient): Response
    {
        $response = $htppClient->request('GET', 'https://chuckn.neant.be/api/rand');
        $blague = json_decode($response->getContent(),true)['joke'];

        return $this->render('home/index.html.twig', [
            'blague' => $blague,
        ]);
    }
}
