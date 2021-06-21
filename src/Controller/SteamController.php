<?php

namespace App\Controller;

use DateTime;
use App\Entity\Jeux;
use App\Form\GameType;
use App\Repository\JeuxRepository;

use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Integer;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        return $this->redirectToRoute('steam_store');
    }

    /**
     * @Route("/steam/store", name="steam_store")
     */
    public function store(JeuxRepository $repo, CategoryRepository $repoCat, Request $request): Response
    {
        $games = $repo->findAll();
        $category = $repoCat->findAll();

        $filters = $request->get('category');

        $gameToDisplay = $repo->findByCategory($filters);

        if(empty($filters)) {
            $gameToDisplay = $repo->findAll();
        }

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('steam/_content.html.twig', compact('gameToDisplay', 
                'games'))
            ]);
        }

        return $this->render('steam/store.html.twig', compact('gameToDisplay', 'games', 'category'));
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
     * @Route("/steam/new", name="steam_create")
     * @Route("/steam/{id}/edit", name="steam_edit")
     */
    public function form(Jeux $game = null, Request $request, EntityManagerInterface $manager)
    {

        $user = $this->getUser();

        if (!$game) {
            $game = new Jeux();
        }

        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$game->getId()) {
                $game->setGameCreator($user->getUsername());
            }

            if (!$game->getDate()) {
                $game->setDate(date("d-m-Y"));
            }

            $manager->persist($game);
            $manager->flush();

            return $this->redirectToRoute('steam_game', ['id' => $game->getId()]);
        }

        return $this->render(
            'steam/create.html.twig',
            [
                'formGame' => $form->createView(),
                'editMode' => $game->getId() !== null
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
