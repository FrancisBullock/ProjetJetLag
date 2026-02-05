<?php

namespace App\Entity;

use App\Repository\GameSessionRepository;
use App\Entity\User;
use App\Entity\Question;
use App\Entity\Deck;
use App\Entity\Player;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Question::class)]
    private Collection $consumedQuestions;

    #[ORM\OneToOne(inversedBy: 'gameSession', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $hiderBonusTime = 0;

    public function __construct()
    {
        $this->consumedQuestions = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getStartTime(): ?\DateTime { return $this->startTime; }

    public function setStartTime(?\DateTime $startTime): static { $this->startTime = $startTime; return $this; }

    public function getCurrentHider(): ?Player { return $this->currentHider; }

    public function setCurrentHider(?Player $currentHider): static { $this->currentHider = $currentHider; return $this; }

    public function getDeck(): ?Deck { return $this->deck; }

    public function setDeck(?Deck $deck): static
    {
        if ($deck === null && $this->deck !== null) { $this->deck->setGameSession(null); }
        if ($deck !== null && $deck->getGameSession() !== $this) { $deck->setGameSession($this); }
        $this->deck = $deck;
        return $this;
    }

    public function getConsumedQuestions(): Collection { return $this->consumedQuestions; }

    public function addConsumedQuestion(Question $consumedQuestion): static
    {
        if (!$this->consumedQuestions->contains($consumedQuestion)) {
            $this->consumedQuestions->add($consumedQuestion);
        }
        return $this;
    }

    public function removeConsumedQuestion(Question $consumedQuestion): static
    {
        $this->consumedQuestions->removeElement($consumedQuestion);
        return $this;
    }

    public function getUser(): ?User { return $this->user; }

    public function setUser(User $user): static { $this->user = $user; return $this; }

    public function getHiderBonusTime(): int { return $this->hiderBonusTime; }

    public function setHiderBonusTime(int $hiderBonusTime): static { $this->hiderBonusTime = $hiderBonusTime; return $this; }
}

