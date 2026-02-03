<?php

namespace App\Entity;

use App\Repository\GameSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameSessionRepository::class)]
class GameSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $startTime = null;

    #[ORM\ManyToOne]
    private ?Player $currentHider = null;

    #[ORM\OneToOne(mappedBy: 'gameSession', cascade: ['persist', 'remove'])]
    private ?Deck $deck = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTime $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getCurrentHider(): ?Player
    {
        return $this->currentHider;
    }

    public function setCurrentHider(?Player $currentHider): static
    {
        $this->currentHider = $currentHider;

        return $this;
    }

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): static
    {
        // unset the owning side of the relation if necessary
        if ($deck === null && $this->deck !== null) {
            $this->deck->setGameSession(null);
        }

        // set the owning side of the relation if necessary
        if ($deck !== null && $deck->getGameSession() !== $this) {
            $deck->setGameSession($this);
        }

        $this->deck = $deck;

        return $this;
    }
}
