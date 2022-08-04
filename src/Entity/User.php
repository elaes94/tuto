<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message:'Champ obligatoire')]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'contact')]
    private $activities;

    #[ORM\Column(type: 'string', length: 25)]
    #[Assert\NotBlank(message:'Champ obligatoire')]
    // #[Assert\NotNull(message:'enter things')]
    #[Assert\Length(
        min: 3, max: 25,
        minMessage: 'Votre prénom doit avoir au moins {{ limit }} caractères.',
        maxMessage: 'Votre prénom ne doit pas avoir plus de {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/(^[a-zA-ZÀ-Ÿ\-. ]*$)/',
        message: 'Votre prénom contient des caractères non valide.',
    )]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:'Champ obligatoire')]
    #[Assert\Length(
        min: 3, max: 25,
        minMessage: 'Votre nom doit avoir au moins {{ limit }} caractères.',
        maxMessage: 'Votre nom ne doit pas avoir plus de {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/(^[a-zA-ZÀ-Ÿ\-. ]*$)/',
        message: 'Votre nom contient des caractères non valide.',
    )]
    private $lastname;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message:'Champ obligatoire')]
    #[Assert\Regex(
        pattern: '/(^[24-9]{1}[0-9]{1}(?:[\s.-]*\d{2}){3}$)/',
        message: 'Votre numéro n\'est pas valide',
    )]
    private $phone_1;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Regex(
        pattern: '/(^[24-9]{1}[0-9]{1}(?:[\s.-]*\d{2}){3}$)/',
        message: 'Votre numéro n\'est pas valide',
    )]
    private $phone_2;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $social;

    #[ORM\Column(type: 'string', length: 25)]
    #[Assert\Choice(['membre', 'professionnel'], message: 'La catégorie n\'est pas valide.')]
    private $categorie;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    // public function addActivity(Activity $activity): self
    // {
    //     if (!$this->activities->contains($activity)) {
    //         $this->activities[] = $activity;
    //         $activity->addContact($this);
    //     }

    //     return $this;
    // }

    // public function removeActivity(Activity $activity): self
    // {
    //     if ($this->activities->removeElement($activity)) {
    //         $activity->removeContact($this);
    //     }

    //     return $this;
    // }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone1(): ?string
    {
        $this->phone_1 = chunk_split($this->phone_1, 2, ' ');
        return $this->phone_1;
    }

    public function setPhone1(string $phone_1): self
    {
        $phone_1 = preg_replace('/[\s.-]/', '', $phone_1);
        
        $this->phone_1 = $phone_1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        $this->phone_2 = chunk_split($this->phone_2, 2, ' ');
        return $this->phone_2;
    }

    public function setPhone2(?string $phone_2): self
    {
        $phone_2 = preg_replace('/[\s.-]/', '', $phone_2);
        $this->phone_2 = $phone_2;

        return $this;
    }

    public function getSocial(): ?string
    {
        return $this->social;
    }

    public function setSocial(?string $social): self
    {
        $this->social = $social;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
