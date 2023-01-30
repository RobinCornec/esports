<?php

namespace App\Entity;

use App\Enum\WinnerTypeEnum;
use App\Repository\TournamentRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 127, unique: true)]
    private string $slug;

    #[ORM\Column(length: 32, unique: true)]
    private string $prizePool;

    #[ORM\Column]
    private ?DateTime $beginAt;

    #[ORM\Column]
    private ?DateTime $endAt;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    private Team $winner;

    #[ORM\Column]
    private WinnerTypeEnum $winnerType;

    #[ORM\ManyToOne(targetEntity: Serie::class, inversedBy: 'tournaments')]
    private Serie $serie;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: Contest::class)]
    private Collection $contests;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
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
    public function getPrizePool(): string
    {
        return $this->prizePool;
    }

    /**
     * @param string $prizePool
     */
    public function setPrizePool(string $prizePool): void
    {
        $this->prizePool = $prizePool;
    }

    /**
     * @return DateTime|null
     */
    public function getBeginAt(): ?DateTime
    {
        return $this->beginAt;
    }

    /**
     * @param DateTime|null $beginAt
     */
    public function setBeginAt(?DateTime $beginAt): void
    {
        $this->beginAt = $beginAt;
    }

    /**
     * @return DateTime|null
     */
    public function getEndAt(): ?DateTime
    {
        return $this->endAt;
    }

    /**
     * @param DateTime|null $endAt
     */
    public function setEndAt(?DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }

    /**
     * @return Team
     */
    public function getWinner(): Team
    {
        return $this->winner;
    }

    /**
     * @param Team $winner
     */
    public function setWinner(Team $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * @return WinnerTypeEnum
     */
    public function getWinnerType(): WinnerTypeEnum
    {
        return $this->winnerType;
    }

    /**
     * @param WinnerTypeEnum $winnerType
     */
    public function setWinnerType(WinnerTypeEnum $winnerType): void
    {
        $this->winnerType = $winnerType;
    }

    /**
     * @return Serie
     */
    public function getSerie(): Serie
    {
        return $this->serie;
    }

    /**
     * @param Serie $serie
     */
    public function setSerie(Serie $serie): void
    {
        $this->serie = $serie;
    }

    /**
     * @return Collection
     */
    public function getContests(): Collection
    {
        return $this->contests;
    }

    /**
     * @param Collection $contests
     */
    public function setContests(Collection $contests): void
    {
        $this->contests = $contests;
    }

}
