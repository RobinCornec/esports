<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use App\Repository\SeriesRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity(repositoryClass: SeriesRepository::class)]
class Series
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private bool $ended = false;

    #[ORM\Column]
    private int $leagueId;

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: Tournaments::class)]
    private Collection $tournaments;

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
    public function getLeagueId(): int
    {
        return $this->leagueId;
    }

    /**
     * @param int $leagueId
     */
    public function setLeagueId(int $leagueId): void
    {
        $this->leagueId = $leagueId;
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
     * @param array       $apiSerie
     * @param Series|null $serie
     * @param int         $pandaScoreId
     *
     * @return $this
     * @throws Exception
     */
    public function create(array $apiSerie, int $pandaScoreId, ?Series $serie): self {
        if (null === $serie) {
            $serie = new self();
        }

        $serie->setName($apiSerie['full_name']);

        if (null !== $apiSerie['end_at']) {
            $endDate = new DateTime($apiSerie['end_at']);
            $serie->setEnded($endDate < new DateTime());
        }

        $serie->setLeagueId($apiSerie['league']['id']);
        $serie->setPandaScoreId($pandaScoreId);

        return $serie;
    }

}
