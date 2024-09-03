<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HttpClientInterface $httpClient): Response
    {
        $response = $httpClient->request('GET', 'https://chuckn.neant.be/api/rand');
        $blague = json_decode($response->getContent(),true)['joke'];

        $response2 = $httpClient->request('GET', 'https://api-adresse.data.gouv.fr/search/?q=8+bd+du+port+Cergy');
        $latitude = json_decode($response2->getContent(), true)['features'][0]['geometry']['coordinates'][0];
        $longitude = json_decode($response2->getContent(), true)['features'][0]['geometry']['coordinates'][1];

        return $this->render('home/index.html.twig', [
            'blague' => $blague,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
    }

}
