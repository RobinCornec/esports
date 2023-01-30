<?php

namespace App\Entity;

use App\Enum\TierEnum;
use App\Enum\WinnerTypeEnum;
use App\Repository\SerieRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie
{
    #[ORM\Id]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private ?DateTime $beginAt;

    #[ORM\Column]
    private ?DateTime $endAt;

    #[ORM\Column(length: 63, unique: true)]
    private string $slug;

    #[ORM\Column]
    private int $winnerId;

    #[ORM\Column]
    private WinnerTypeEnum $winnerType;

    #[ORM\Column(length: 8)]
    private TierEnum $tiers;

    #[ORM\ManyToOne(targetEntity: League::class, inversedBy: 'series')]
    private League $league;

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: Tournament::class)]
    private Collection $tournaments;

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
     * @return int
     */
    public function getWinnerId(): int
    {
        return $this->winnerId;
    }

    /**
     * @param int $winnerId
     */
    public function setWinnerId(int $winnerId): void
    {
        $this->winnerId = $winnerId;
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
     * @return TierEnum
     */
    public function getTiers(): TierEnum
    {
        return $this->tiers;
    }

    /**
     * @param TierEnum $tiers
     */
    public function setTiers(TierEnum $tiers): void
    {
        $this->tiers = $tiers;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }

    /**
     * @param League $league
     */
    public function setLeague(League $league): void
    {
        $this->league = $league;
    }

    /**
     * @return Collection
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    /**
     * @param Collection $tournaments
     */
    public function setTournaments(Collection $tournaments): void
    {
        $this->tournaments = $tournaments;
    }
}
