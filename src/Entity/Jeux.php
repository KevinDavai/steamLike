<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JeuxRepository::class)
 */
class Jeux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_creator;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameName(): ?string
    {
        return $this->game_name;
    }

    public function setGameName(string $game_name): self
    {
        $this->game_name = $game_name;

        return $this;
    }

    public function getGameDescription(): ?string
    {
        return $this->game_description;
    }

    public function setGameDescription(string $game_description): self
    {
        $this->game_description = $game_description;

        return $this;
    }

    public function getGameCreator(): ?string
    {
        return $this->game_creator;
    }

    public function setGameCreator(string $game_creator): self
    {
        $this->game_creator = $game_creator;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
