<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="`character`")
 */
class Character
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
     * @Groups({"tvshows", "characters"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=80)
     * @Groups({"tvshows", "characters"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"tvshows", "characters"})
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"tvshows", "characters"})
     */
    private $bio;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"tvshows", "characters"})
     */
    private $age;

    /**
     * @ORM\ManyToMany(targetEntity=TvShow::class, mappedBy="characters")
     */
    private $tvShows;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"tvshows", "characters"})
     */
    private $pictureFilename;

    public function __construct()
    {
        $this->tvShows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection|TvShow[]
     */
    public function getTvShows(): Collection
    {
        return $this->tvShows;
    }

    public function addTvShow(TvShow $tvShow): self
    {
        if (!$this->tvShows->contains($tvShow)) {
            $this->tvShows[] = $tvShow;
            $tvShow->addCharacter($this);
        }

        return $this;
    }

    public function removeTvShow(TvShow $tvShow): self
    {
        if ($this->tvShows->removeElement($tvShow)) {
            $tvShow->removeCharacter($this);
        }

        return $this;
    }

    /**
     * Si l'on tente de faire un echo sur l'objet Character, PHP retournera la valeur du prénom
     */
    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getPictureFilename(): ?string
    {
        return $this->pictureFilename;
    }

    public function setPictureFilename(?string $pictureFilename): self
    {
        $this->pictureFilename = $pictureFilename;

        return $this;
    }
}
