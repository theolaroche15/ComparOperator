<?php

declare(strict_types=1);

class Destination
{
    private int $id;
    private string $location;
    private float $price;
    private int $tourOperator_id;

    public function __construct(int $id, string $location, float $price, int $tourOperator_id)
    {
        $this->id = $id;
        $this->location = $location;
        $this->price = $price;
        $this->tourOperator_id = $tourOperator_id;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getLocation(): string
    {
        return $this->location;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getTourOperatorId(): int
    {
        return $this->tourOperator_id;
    }
}
