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


/**
 * @ORM\Table(name="users")
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message= "Plase enter a valid email address.")
     * @Assert\Email()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message = "Please enter a valid password")
     *  @Assert\Length(max=4096)
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message= "valid first name is required.")
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @Assert\NotBlank(message= "valid last name is required.")
     * @ORM\Column(type="string", length=45)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VimeoApiKey;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Video", mappedBy="usersThatLike")
     * @ORM\JoinTable(name="likes")
     */
    private $likedVideos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Video", mappedBy="usersThatDontLike")
     * @ORM\JoinTable(name="dislikes")
     */
    private $dislikedVideos;

    public function __construct()
    {
        $this->likedVideos = new ArrayCollection();
        $this->dislikedVideos = new ArrayCollection();
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getVimeoApiKey(): ?string
    {
        return $this->VimeoApiKey;
    }

    public function setVimeoApiKey(?string $VimeoApiKey): self
    {
        $this->VimeoApiKey = $VimeoApiKey;

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getLikedVideos(): Collection
    {
        return $this->likedVideos;
    }

    public function addLikedVideo(Video $likedVideo): self
    {
        if (!$this->likedVideos->contains($likedVideo)) {
            $this->likedVideos[] = $likedVideo;
            $likedVideo->addUsersThatLike($this);
        }

        return $this;
    }

    public function removeLikedVideo(Video $likedVideo): self
    {
        if ($this->likedVideos->contains($likedVideo)) {
            $this->likedVideos->removeElement($likedVideo);
            $likedVideo->removeUsersThatLike($this);
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getDislikedVideos(): Collection
    {
        return $this->dislikedVideos;
    }

    public function addDislikedVideo(Video $dislikedVideo): self
    {
        if (!$this->dislikedVideos->contains($dislikedVideo)) {
            $this->dislikedVideos[] = $dislikedVideo;
            $dislikedVideo->addUsersThatDontLike($this);
        }

        return $this;
    }

    public function removeDislikedVideo(Video $dislikedVideo): self
    {
        if ($this->dislikedVideos->contains($dislikedVideo)) {
            $this->dislikedVideos->removeElement($dislikedVideo);
            $dislikedVideo->removeUsersThatDontLike($this);
        }

        return $this;
    }
}
