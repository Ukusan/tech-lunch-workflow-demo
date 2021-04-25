<?php

namespace App\Entity;

use App\Repository\TransitionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransitionRepository::class)
 */
class Transition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ticket::class, inversedBy="transitions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $stateForm;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $stateTo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeStamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getStateForm(): ?string
    {
        return $this->stateForm;
    }

    public function setStateForm(string $stateForm): self
    {
        $this->stateForm = $stateForm;

        return $this;
    }

    public function getStateTo(): ?string
    {
        return $this->stateTo;
    }

    public function setStateTo(string $stateTo): self
    {
        $this->stateTo = $stateTo;

        return $this;
    }

    public function getTimeStamp(): ?\DateTimeInterface
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(\DateTimeInterface $timeStamp): self
    {
        $this->timeStamp = $timeStamp;

        return $this;
    }
}
