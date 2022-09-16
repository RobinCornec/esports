<?php

namespace App\Entity;

use App\Repository\TournamentsRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity(repositoryClass: TournamentsRepository::class)]
class Tournaments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private bool $ended = false;

    #[ORM\ManyToOne(targetEntity: Series::class, inversedBy: 'tournaments')]
    private Series $serie;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: Matches::class)]
    private Collection $matches;

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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Series
     */
    public function getSerie(): Series
    {
        return $this->serie;
    }

    /**
     * @param Series $serie
     */
    public function setSerie(Series $serie): void
    {
        $this->serie = $serie;
    }

    /**
     * @return Collection
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    /**
     * @param Collection $matches
     */
    public function setMatches(Collection $matches): void
    {
        $this->matches = $matches;
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
     * @param array            $apiTournament
     * @param int              $pandaScoreId
     * @param Tournaments|null $tournament
     * @param Series           $entitySerie
     *
     * @return $this
     * @throws Exception
     */
    public function create(array $apiTournament, int $pandaScoreId, Series $entitySerie, ?Tournaments $tournament): self {
        if (null === $tournament) {
            $tournament = new self();
        }

        $tournament->setName($apiTournament['name']);

        if (null !== $apiTournament['end_at']) {
            $endDate = new DateTime($apiTournament['end_at']);
            $tournament->setEnded($endDate < new DateTime());
        }

        $tournament->setPandaScoreId($pandaScoreId);
        $tournament->setSerie($entitySerie);

        return $tournament;
    }

}
