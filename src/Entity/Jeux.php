<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

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
     * @Assert\Length(min=3, max=255, minMessage="Votre titre est trop court", maxMessage="Votre titre est trop long")
     */
    private $game_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Votre description est trop courte")
     */
    private $game_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_creator;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $price;

    /**
     * @ORM\Column(type="string")
     */
    private $date_sortie;

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

    public function getDate(): ?string
    {
        return $this->date_sortie;
    }

    public function setDate(string $date_sortie): self
    {
        $this->date_sortie = $date_sortie;

        return $this;
    }
}
