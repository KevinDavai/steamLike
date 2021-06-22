<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields={"email"},
 *  message="L'email que vous avez indiqué est déjà utilisé !"
 * )
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="User")
     *
     */
    private $groups;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3,  
     *      max = 10,
     *      minMessage = "Votre nom d'utilisateur doit contenir au minimum {{ limit }} charactères",
     *      maxMessage = "Votre nom d'utilisateur doit contenir au maximum {{ limit }} charactères"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     *  
     * @Assert\Length(
     *      min = 8,  
     *      minMessage = "Votre mot de passe doit contenir au minimum {{ limit }} charactères",
     *      maxMessage = "Votre mot de passe doit contenir au maximum {{ limit }} charactères"
     * )
     *
     */
    private $password;

    protected $captcha;

     /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Regex(pattern="/^[a-zA-Z0-9 ]+$/", match=true, message="Votre nom ne peux pas contenir de charactères speciaux")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Regex(pattern="/^[a-zA-Z0-9 ]+$/", match=true, message="Votre prénom ne peux pas contenir de charactères speciaux")
     */
    private $firstname;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }
    
    public function getRolesUser()
    {
        return $this->groups->toArray();
    }

    public function getCaptcha()
    {
      return $this->captcha;
    }

    public function setCaptcha($captchaCode)
    {
      $this->captcha = $captchaCode;
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function getRoles() {
        return ['ROLE_USER'];
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}
