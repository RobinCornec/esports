<?php

namespace App\Entity;

use App\Repository\ContestRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContestRepository::class)]
class Contest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private bool $draw;

    #[ORM\Column]
    private bool $forfeit;

    #[ORM\Column]
    private ?DateTime $scheduledAt;

    #[ORM\Column]
    private ?DateTime $beginAt;

    #[ORM\Column]
    private ?DateTime $endAt;

    #[ORM\Column(length: 127, unique: true)]
    private string $slug;

    #[ORM\Column]
    private int $numberOfGames;

    #[ORM\Column(nullable: true)]
    private ?int $team1Id;

    #[ORM\Column(nullable: true)]
    private ?int $team2Id;

    #[ORM\Column]
    private int $team1Score = 0;

    #[ORM\Column]
    private int $team2Score = 0;

    #[ORM\ManyToOne(targetEntity: Tournament::class, inversedBy: 'contests')]
    private Tournament $tournament;

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
     * @return bool
     */
    public function isDraw(): bool
    {
        return $this->draw;
    }

    /**
     * @param bool $draw
     */
    public function setDraw(bool $draw): void
    {
        $this->draw = $draw;
    }

    /**
     * @return bool
     */
    public function isForfeit(): bool
    {
        return $this->forfeit;
    }

    /**
     * @param bool $forfeit
     */
    public function setForfeit(bool $forfeit): void
    {
        $this->forfeit = $forfeit;
    }

    /**
     * @return DateTime|null
     */
    public function getScheduledAt(): ?DateTime
    {
        return $this->scheduledAt;
    }

    /**
     * @param DateTime|null $scheduledAt
     */
    public function setScheduledAt(?DateTime $scheduledAt): void
    {
        $this->scheduledAt = $scheduledAt;
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
    public function getNumberOfGames(): int
    {
        return $this->numberOfGames;
    }

    /**
     * @param int $numberOfGames
     */
    public function setNumberOfGames(int $numberOfGames): void
    {
        $this->numberOfGames = $numberOfGames;
    }

    /**
     * @return int|null
     */
    public function getTeam1Id(): ?int
    {
        return $this->team1Id;
    }

    /**
     * @param int|null $team1Id
     */
    public function setTeam1Id(?int $team1Id): void
    {
        $this->team1Id = $team1Id;
    }

    /**
     * @return int|null
     */
    public function getTeam2Id(): ?int
    {
        return $this->team2Id;
    }

    /**
     * @param int|null $team2Id
     */
    public function setTeam2Id(?int $team2Id): void
    {
        $this->team2Id = $team2Id;
    }

    /**
     * @return int
     */
    public function getTeam1Score(): int
    {
        return $this->team1Score;
    }

    /**
     * @param int $team1Score
     */
    public function setTeam1Score(int $team1Score): void
    {
        $this->team1Score = $team1Score;
    }

    /**
     * @return int
     */
    public function getTeam2Score(): int
    {
        return $this->team2Score;
    }

    /**
     * @param int $team2Score
     */
    public function setTeam2Score(int $team2Score): void
    {
        $this->team2Score = $team2Score;
    }

    /**
     * @return Tournament
     */
    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    /**
     * @param Tournament $tournament
     */
    public function setTournament(Tournament $tournament): void
    {
        $this->tournament = $tournament;
    }

}
