<?php

namespace App\Entity;

use App\Entity\Video;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="categories")
 * @UniqueEntity("name")
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
     * @Assert\NotBlank(message = "The name of category is required")
     * @ORM\Column(type="string", length=45 , unique = true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="subcategorie")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",
     * onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent")
     */
    private $subcategorie;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="category")
     */
    private $videos;

    public function __construct()
    {
        $this->subcategorie = new ArrayCollection();
        $this->videos = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getSubcategorie(): Collection
    {
        return $this->subcategorie;
    }

    public function addSubcategorie(Category $subcategorie): self
    {
        if (!$this->subcategorie->contains($subcategorie)) {
            $this->subcategorie[] = $subcategorie;
            $subcategorie->setParent($this);
        }

        return $this;
    }

    public function removeSubcategorie(Category $subcategorie): self
    {
        if ($this->subcategorie->removeElement($subcategorie)) {
            // set the owning side to null (unless already changed)
            if ($subcategorie->getParent() === $this) {
                $subcategorie->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setCategory($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getCategory() === $this) {
                $video->setCategory(null);
            }
        }

        return $this;
    }
}
