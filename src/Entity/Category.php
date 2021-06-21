<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Jeux::class, mappedBy="category")
     */
    private $Jeux;

    public function __construct()
    {
        $this->Jeux = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Jeux[]
     */
    public function getJeux(): Collection
    {
        return $this->Jeux;
    }

    public function addJeux(Jeux $jeux): self
    {
        if (!$this->Jeux->contains($jeux)) {
            $this->Jeux[] = $jeux;
            $jeux->setCategory($this);
        }

        return $this;
    }

    public function removeJeux(Jeux $jeux): self
    {
        if ($this->Jeux->removeElement($jeux)) {
            // set the owning side to null (unless already changed)
            if ($jeux->getCategory() === $this) {
                $jeux->setCategory(null);
            }
        }

        return $this;
    }
}
