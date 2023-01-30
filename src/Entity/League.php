<?php

namespace App\Entity;

use App\Repository\LeagueRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeagueRepository::class)]
class League
{
    #[ORM\Id]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 63)]
    private string $name;

    #[ORM\Column(length: 63, unique: true)]
    private string $slug;

    #[ORM\Column(nullable: true)]
    private ?string $image;

    #[ORM\Column(nullable: true)]
    private ?string $url;

    #[ORM\Column]
    private bool $activate = false;

    #[ORM\ManyToOne(targetEntity: Videogame::class, inversedBy: 'leagues')]
    private Videogame $videogame;

    #[ORM\OneToMany(mappedBy: 'league', targetEntity: Serie::class)]
    private Collection $series;

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
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function isActivate(): bool
    {
        return $this->activate;
    }

    /**
     * @param bool $activate
     */
    public function setActivate(bool $activate): void
    {
        $this->activate = $activate;
    }

    /**
     * @return Videogame
     */
    public function getVideogame(): Videogame
    {
        return $this->videogame;
    }

    /**
     * @param Videogame $videogame
     */
    public function setVideogame(Videogame $videogame): void
    {
        $this->videogame = $videogame;
    }

    /**
     * @return Collection
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    /**
     * @param Collection $series
     */
    public function setSeries(Collection $series): void
    {
        $this->series = $series;
    }
}
