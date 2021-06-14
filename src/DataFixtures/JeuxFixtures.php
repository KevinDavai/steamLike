<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Jeux;

class JeuxFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $game = new Jeux();
            $game->setGameName("Jeux n°$i")
                ->setGameDescription("Contenu de l'article n°$i")
                ->setGameCreator("Créateur du jeux n°$i")
                ->setPrice(1);

            $manager->persist($game);
        }

        $manager->flush();
    }
}
