<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchesRepository::class)]
class Matches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $team1Id;

    #[ORM\Column]
    private int $team2Id;

    #[ORM\Column]
    private int $team1Score;

    #[ORM\Column]
    private int $team2Score;

    #[ORM\Column]
    private string $team1Name;

    #[ORM\Column]
    private string $team2Name;

    #[ORM\Column]
    private bool $ended;

    #[ORM\Column]
    private int $pandaScoreId;

    public function getId(): ?int
    {
        return $this->id;
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
     * @return int
     */
    public function getTeam1Id(): int
    {
        return $this->team1Id;
    }

    /**
     * @param int $team1Id
     */
    public function setTeam1Id(int $team1Id): void
    {
        $this->team1Id = $team1Id;
    }

    /**
     * @return int
     */
    public function getTeam2Id(): int
    {
        return $this->team2Id;
    }

    /**
     * @param int $team2Id
     */
    public function setTeam2Id(int $team2Id): void
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
     * @return string
     */
    public function getTeam1Name(): string
    {
        return $this->team1Name;
    }

    /**
     * @param string $team1Name
     */
    public function setTeam1Name(string $team1Name): void
    {
        $this->team1Name = $team1Name;
    }

    /**
     * @return string
     */
    public function getTeam2Name(): string
    {
        return $this->team2Name;
    }

    /**
     * @param string $team2Name
     */
    public function setTeam2Name(string $team2Name): void
    {
        $this->team2Name = $team2Name;
    }

    /**
     * @return bool
     */
    public function isEnded(): bool
    {
        return $this->ended;
    }

    /**
     * @param bool $ended
     */
    public function setEnded(bool $ended): void
    {
        $this->ended = $ended;
    }

    /**
     * @return int
     */
    public function getPandaScoreId(): int
    {
        return $this->pandaScoreId;
    }

    /**
     * @param int $pandaScoreId
     */
    public function setPandaScoreId(int $pandaScoreId): void
    {
        $this->pandaScoreId = $pandaScoreId;
    }

}
