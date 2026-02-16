<?php

declare(strict_types=1);

class TourOperator
{
    private int $id;
    private string $name;
    private string $link;
    private int $gradeCount;
    private int $gradeTotal;
    private bool $isPremium;

    public function __construct(int $id, string $name, string $link, int $gradeCount, int $gradeTotal, bool $isPremium)
    {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
        $this->gradeCount = $gradeCount;
        $this->gradeTotal = $gradeTotal;
        $this->isPremium = $isPremium;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getLink(): string
    {
        return $this->link;
    }
    public function getGradeCount(): int
    {
        return $this->gradeCount;
    }
    public function getGradeTotal(): int
    {
        return $this->gradeTotal;
    }
    public function getIsPremium(): bool
    {
        return $this->isPremium;
    }
}
