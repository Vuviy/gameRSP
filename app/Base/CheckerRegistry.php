<?php

namespace App\Base;

use App\Base\Interface\AchievementCheckerInterface;
use Exception;

class CheckerRegistry
{

    private array $checkers = [];

    public function register(string $name, AchievementCheckerInterface $checker): void
    {
        $this->checkers[$name] = $checker;
    }

    public function get(string $name): ?AchievementCheckerInterface
    {
        return $this->checkers[$name] ?? null;
    }

    public function buildChain(array $checkerNames): ?AchievementCheckerInterface
    {
        $previous = null;
        $first = null;

        foreach ($checkerNames as $name) {
            $checker = $this->get($name);
            if (!$checker) {
                throw new Exception("Checker '$name' not found");
            }

            if ($previous) {
                $previous->setNext($checker);
            } else {
                $first = $checker;
            }

            $previous = $checker;
        }

        return $first;
    }
}