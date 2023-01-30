<?php

namespace App\Entity;

use App\Repository\VideogameRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideogameRepository::class)]
class Videogame
{
    #[ORM\Id]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 63)]
    private string $name;

    #[ORM\Column(length: 63, unique: true)]
    private string $slug;

    #[ORM\Column(nullable: true)]
    private string $image;

    #[ORM\OneToMany(mappedBy: 'videogame', targetEntity: League::class)]
    private ?Collection $leagues = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|null
     */
    public function getLeagues(): ?Collection
    {
        return $this->leagues;
    }

    /**
     * @param Collection|null $leagues
     */
    public function setLeagues(?Collection $leagues): void
    {
        $this->leagues = $leagues;
    }

}
