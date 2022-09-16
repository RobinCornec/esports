<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;

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
    private int $numberOfGames;

    #[ORM\Column(nullable: true)]
    private ?int $team1Id;

    #[ORM\Column(nullable: true)]
    private ?int $team2Id;

    #[ORM\Column]
    private int $team1Score = 0;

    #[ORM\Column]
    private int $team2Score = 0;

    #[ORM\Column(nullable: true)]
    private ?string $team1Name = null;

    #[ORM\Column(nullable: true)]
    private ?string $team1Logo = null;

    #[ORM\Column(nullable: true)]
    private ?string $team2Name = null;

    #[ORM\Column(nullable: true)]
    private ?string $team2Logo = null;

    #[ORM\Column]
    private bool $ended = false;

    #[ORM\ManyToOne(targetEntity: Tournaments::class, inversedBy: 'matches')]
    private Tournaments $tournament;

    #[ORM\Column(nullable: true)]
    private ?DateTime $beginAt;

    #[ORM\Column(unique: true)]
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
     * @return string|null
     */
    public function getTeam1Name(): ?string
    {
        return $this->team1Name;
    }

    /**
     * @param string|null $team1Name
     */
    public function setTeam1Name(?string $team1Name): void
    {
        $this->team1Name = $team1Name;
    }

    /**
     * @return string|null
     */
    public function getTeam1Logo(): ?string
    {
        return $this->team1Logo;
    }

    /**
     * @param string|null $team1Logo
     */
    public function setTeam1Logo(?string $team1Logo): void
    {
        $this->team1Logo = $team1Logo;
    }

    /**
     * @return string|null
     */
    public function getTeam2Name(): ?string
    {
        return $this->team2Name;
    }

    /**
     * @param string|null $team2Name
     */
    public function setTeam2Name(?string $team2Name): void
    {
        $this->team2Name = $team2Name;
    }

    /**
     * @return string|null
     */
    public function getTeam2Logo(): ?string
    {
        return $this->team2Logo;
    }

    /**
     * @param string|null $team2Logo
     */
    public function setTeam2Logo(?string $team2Logo): void
    {
        $this->team2Logo = $team2Logo;
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
     * @return Tournaments
     */
    public function getTournament(): Tournaments
    {
        return $this->tournament;
    }

    /**
     * @param Tournaments $tournament
     */
    public function setTournament(Tournaments $tournament): void
    {
        $this->tournament = $tournament;
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

    /**
     * @param array        $apiMatch
     * @param int          $pandaScoreId
     * @param Tournaments  $entityTournament
     * @param Matches|null $match
     *
     * @return $this
     * @throws Exception
     */
    public function create(array $apiMatch, int $pandaScoreId, Tournaments $entityTournament, ?Matches $match): self {
        if (null === $match) {
            $match = new self();
        }

        $match->setName($apiMatch['name']);
        $match->setNumberOfGames($apiMatch['number_of_games']);

        if (null !== $apiMatch['end_at']) {
            $endDate = new DateTime($apiMatch['end_at']);
            $match->setEnded($endDate < new DateTime());
        }

        if (!empty($apiMatch['opponents'][0])) {
            $opponent1 = $apiMatch['opponents'][0]['opponent'];
            $match->setTeam1Id($opponent1['id']);
            $match->setTeam1Name($opponent1['name']);
            $match->setTeam1Logo($opponent1['image_url']);
        }

        if (!empty($apiMatch['results'][0])) {
            $match->setTeam1Score($apiMatch['results'][0]['score']);
        }

        if (!empty($apiMatch['opponents'][1])) {
            $opponent2 = $apiMatch['opponents'][1]['opponent'];
            $match->setTeam2Id($opponent2['id']);
            $match->setTeam2Name($opponent2['name']);
            $match->setTeam2Logo($opponent2['image_url']);
        }

        if (!empty($apiMatch['results'][1])) {
            $match->setTeam2Score($apiMatch['results'][1]['score']);
        }

        $match->setBeginAt(new DateTime($apiMatch['begin_at']));
        $match->setPandaScoreId($pandaScoreId);
        $match->setTournament($entityTournament);

        return $match;
    }

}
