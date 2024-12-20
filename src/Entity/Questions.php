<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(length: 255)]
    private ?string $question_a = null;

    #[ORM\Column(length: 255)]
    private ?string $question_b = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question_c = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question_d = null;

    #[ORM\Column(length: 255)]
    private ?string $correctAnswer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getQuestionA(): ?string
    {
        return $this->question_a;
    }

    public function setQuestionA(string $question_a): static
    {
        $this->question_a = $question_a;

        return $this;
    }

    public function getQuestionB(): ?string
    {
        return $this->question_b;
    }

    public function setQuestionB(string $question_b): static
    {
        $this->question_b = $question_b;

        return $this;
    }

    public function getQuestionC(): ?string
    {
        return $this->question_c;
    }

    public function setQuestionC(?string $question_c): static
    {
        $this->question_c = $question_c;

        return $this;
    }

    public function getQuestionD(): ?string
    {
        return $this->question_d;
    }

    public function setQuestionD(?string $question_d): static
    {
        $this->question_d = $question_d;

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(string $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }
}
