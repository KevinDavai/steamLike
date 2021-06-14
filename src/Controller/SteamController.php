<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Jeux;
use App\Repository\JeuxRepository;

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
    public function store(JeuxRepository $repo): Response
    {
        $games = $repo->findAll();

        return $this->render(
            'steam/store.html.twig',
            [
                'game' => $games
            ]
        );
    }

    /**
     * @Route("/steam/library", name="steam_library")
     */
    public function library(JeuxRepository $repo): Response
    {
        $games = $repo->findAll();

        return $this->render(
            'steam/library.html.twig',
            [
                'game' => $games
            ]
        );
    }


    /**
     * @Route("/steam/{id}", name="steam_game")
     */
    public function game(Jeux $game): Response
    {
        return $this->render(
            'steam/game.html.twig',
            [
                'game' => $game
            ]
        );
    }
}
