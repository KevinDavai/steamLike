<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SteamController extends AbstractController
{
    #[Route('/steam', name: 'steam')]
    public function index(): Response
    {
        return $this->render('steam/index.html.twig', [
            'controller_name' => 'SteamController',
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(): Response 
    {
        return $this->render('steam/home.html.twig');
    }
}
