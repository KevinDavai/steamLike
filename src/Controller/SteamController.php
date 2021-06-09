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

    /**
     * @Route("/steam/store", name="steam_store")
     */
    public function store(): Response
    {
        return $this->render('steam/store.html.twig');
    }

    /**
     * @Route("/steam/library", name="steam_library")
     */
    public function library(): Response
    {
        return $this->render('steam/library.html.twig');
    }
}
