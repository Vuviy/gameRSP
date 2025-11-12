<?php

namespace App\Core\Achievement;

use App\Base\AbstractAchievementChecker;

class FiveWinsStreakChecker extends AbstractAchievementChecker
{
    public function handle(array $games): void
    {
        $wins = count(array_filter($games, fn($g) => $g['result'] === 'win'));
        if ($wins >= 10 && !in_array('10 wins total', $_SESSION['achievements'])) {
            $_SESSION['achievements'][] = '10 wins total';
        }
    }
}