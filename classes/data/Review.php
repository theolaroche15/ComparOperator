<?php

declare(strict_types=1);

class Review
{
    private int $id;
    private string $message;
    private string $author;
    private int $tourOperatorId;

    public function __construct(int $id, string $message, string $author, int $tourOperatorId)
    {
        $this->id = $id;
        $this->message = $message;
        $this->author = $author;
        $this->tourOperatorId = $tourOperatorId;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }
    public function getTourOperatorId(): int
    {
        return $this->tourOperatorId;
    }
}
