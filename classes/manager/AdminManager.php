<?php

declare(strict_types=1);

class AdminManager
{
    private Manager $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function createOperator(string $name, string $link, bool $isPremium): bool|string
    {
        if ($name === '' || $link === '') {
            return 'Tour operator name and link are required';
        }

        return $this->manager->createTourOperator($name, $link, $isPremium)
            ? true
            : 'Error while creating the tour operator';
    }

    public function createDestination(string $location, float $price, int $operatorId): bool|string
    {
        if ($location === '' || $price <= 0 || $operatorId <= 0) {
            return 'All destination fields are required';
        }

        return $this->manager->createDestination($location, $price, $operatorId)
            ? true
            : 'Error while creating the destination';
    }

    public function setPremium(int $operatorId): bool|string
    {
        if ($operatorId <= 0) {
            return 'Please select a tour operator';
        }

        return $this->manager->updateOperatorToPremium($operatorId)
            ? true
            : 'Error while updating the tour operator';
    }

    public function getStats(): array
    {
        $operators = $this->manager->getAllOperators();
        $destinations = $this->manager->getAllDestination();

        $nbOperators = count($operators);
        $nbDestinations = count($destinations);
        $nbPremium = 0;

        foreach ($operators as $op) {
            if (!empty($op['is_premium'])) {
                $nbPremium++;
            }
        }

        return [
            'operators' => $operators,
            'destinations' => $destinations,
            'nbOperators' => $nbOperators,
            'nbDestinations' => $nbDestinations,
            'nbPremium' => $nbPremium
        ];
    }
}
