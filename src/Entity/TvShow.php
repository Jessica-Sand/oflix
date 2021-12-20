<?php

namespace App\Entity;

use App\Repository\TvShowRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TvShowRepository::class)
 */
class TvShow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tvshows", "characters"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="Merci de saisir un titre pour la série")
     * @Groups({"tvshows", "characters"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"tvshows", "characters"})
     * @Assert\NotBlank(message="Merci de saisir un synopsis pour la série")
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     * @Groups({"tvshows", "characters"})
     */
    private $image;

     /**
     * @ORM\Column(type="string", length=80, nullable=true)
     * @Groups({"tvshows", "characters"})
     */
    private $imageurl;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"tvshows", "characters"})
     */
    private $nbLikes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="tvShow", orphanRemoval=true)
     * @ORM\OrderBy({"seasonNumber"="DESC"})
     */
    private $seasons;

    /**
     * @ORM\ManyToMany(targetEntity=Character::class, inversedBy="tvShows")
     */
    private $characters;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="tvShows")
     * @ORM\OrderBy({"name"="DESC"})
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
    */
    private $Poster;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->publishedAt = new DateTime();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->characters = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setNbLikes(?int $nbLikes): self
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Season[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setTvShow($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getTvShow() === $this) {
                $season->setTvShow(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        $this->characters->removeElement($character);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getImageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setImageurl(?string $imageurl): self
    {
        $this->imageurl = $imageurl;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->Poster;
    }

    public function setPoster(?string $Poster): self
    {
        $this->Poster = $Poster;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
