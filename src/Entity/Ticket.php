<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Constant\PRPlace;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=50, options={"default" = PRPlace::DESCRIBE})
     */
    private $currentPlace;

    /**
     * @ORM\OneToMany(targetEntity=Transition::class, mappedBy="ticket", orphanRemoval=true, cascade={"persist"})
     */
    private $transitions;

    public function __construct()
    {
        $this->transitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCurrentPlace(): ?string
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace(string $currentPlace): self
    {
        $this->currentPlace = $currentPlace;

        return $this;
    }

    /**
     * @return Collection|Transition[]
     */
    public function getTransitions(): Collection
    {
        return $this->transitions;
    }

    public function addTransition(Transition $transition): self
    {
        if (!$this->transitions->contains($transition)) {
            $this->transitions[] = $transition;
            $transition->setTicket($this);
        }

        return $this;
    }

    public function removeTransition(Transition $transition): self
    {
        if ($this->transitions->removeElement($transition)) {
            // set the owning side to null (unless already changed)
            if ($transition->getTicket() === $this) {
                $transition->setTicket(null);
            }
        }

        return $this;
    }
}
