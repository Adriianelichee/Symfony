<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
class Answers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column(length: 255)]
    private ?string $ch_answer = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questions $id_question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getChAnswer(): ?string
    {
        return $this->ch_answer;
    }

    public function setChAnswer(string $ch_answer): static
    {
        $this->ch_answer = $ch_answer;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdQuestion(): ?Questions
    {
        return $this->id_question;
    }

    public function setIdQuestion(?Questions $id_question): static
    {
        $this->id_question = $id_question;

        return $this;
    }
}
