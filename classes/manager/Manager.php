<?php

declare(strict_types=1);

class Manager
{
    private PDO $bdd;

    public function __construct(PDO $bdd)
    {
        $this->bdd = $bdd;
    }


    public function getAllDestination(): array
    {
        $query = $this->bdd->prepare("SELECT * FROM destination");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDestination(string $location, float $price, int $tourOperatorId): bool
    {
        $query = $this->bdd->prepare("
            INSERT INTO destination (location, price, tour_operator_id)
            VALUES (:location, :price, :tour_operator_id)
        ");

        $query->bindValue(':location', $location, PDO::PARAM_STR);
        $query->bindValue(':price', $price);
        $query->bindValue(':tour_operator_id', $tourOperatorId, PDO::PARAM_INT);

        return $query->execute();
    }


    public function getOperatorById(int $id): array|false
    {
        $query = $this->bdd->prepare("SELECT * FROM tour_operator WHERE id = :id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllOperators(): array
    {
        $query = $this->bdd->prepare("SELECT * FROM tour_operator");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTourOperator(string $name, string $link, bool $isPremium): bool
    {
        $query = $this->bdd->prepare("
            INSERT INTO tour_operator (name, link, grade_count, grade_total, is_premium)
            VALUES (:name, :link, 0, 0, :is_premium)
        ");

        $query->bindValue(':name', $name, PDO::PARAM_STR);
        $query->bindValue(':link', $link, PDO::PARAM_STR);
        $query->bindValue(':is_premium', $isPremium ? 1 : 0, PDO::PARAM_INT);

        return $query->execute();
    }

    public function updateOperatorToPremium(int $operatorId): bool
    {
        $query = $this->bdd->prepare("
            UPDATE tour_operator
            SET is_premium = 1
            WHERE id = :id
        ");
        $query->bindValue(':id', $operatorId, PDO::PARAM_INT);
        return $query->execute();
    }


    public function createReview(int $operatorId, string $message, string $author, int $stars): bool
    {
        $query = $this->bdd->prepare("
            INSERT INTO review (message, author, tour_operator_id, stars)
            VALUES (:message, :author, :tour_operator_id, :stars)
        ");

        $query->bindValue(':message', $message, PDO::PARAM_STR);
        $query->bindValue(':author', $author, PDO::PARAM_STR);
        $query->bindValue(':tour_operator_id', $operatorId, PDO::PARAM_INT);
        $query->bindValue(':stars', $stars, PDO::PARAM_INT);

        return $query->execute();
    }

    public function getReviewsByOperatorId(int $operatorId): array
    {
        $query = $this->bdd->prepare("
            SELECT * FROM review
            WHERE tour_operator_id = :tour_operator_id
        ");
        $query->bindValue(':tour_operator_id', $operatorId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
