<?php

namespace App\Entity;

use App\Repository\UtilisaleurRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UtilisaleurRepository::class)
 * @ORM\HasLifecycleCallbacks()
 *  @UniqueEntity(
 * fields={"email"},
 * message="Adresse email deja utilisé !"
 * )
 */
class Utilisaleur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Prenom obligatoire !")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Nom obligatoire")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Vérifiez vos mots de passe !")
     */

    public $passwordConfirm;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez entrer un mail valide")
     */


    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="author")
     */
    private $ads;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initSlug(){
        if(empty($this->slug))
        {
            $slugify = new  Slugify();
            $this->slug = $slugify->slugify($this->lastName.' '.$this->firstName);
        }
    }

    public function __construct()
    {
        $this->ads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }


    // gestion des roles
    public function getRoles()
    {
//        $roles = $this->userRoles->map(function($role) {
//            return $role->getTitle();
//        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }
    // recuperation du mot de passe
    public function getPassword()
    {
        return $this->hash;
    }
    // renvoyer le sel du password
    public function getSalt(){}
    // recuperation du nom d'utilisateur
    public function getUsername()
    {
        return $this->email;
    }
    // supprimer les données sensible de l'utilisateur
    public function eraseCredentials(){}



}
