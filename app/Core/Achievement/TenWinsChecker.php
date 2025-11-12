<?php

namespace App\Core\Achivment;

use App\Base\AbstractAchievementChecker;

class TenWinsChecker extends AbstractAchievementChecker
{

    public function handle(array $games): void
    {
        $maxStreak = 0;
        $currentStreak = 0;

        foreach ($games as $game) {
            if ($game['result'] === 'win') {
                $currentStreak++;
                $maxStreak = max($maxStreak, $currentStreak);
            } else {
                $currentStreak = 0;
            }
        }

        if ($maxStreak >= 5 && !in_array('5 wins in a row', $_SESSION['achievements'])) {
            $_SESSION['achievements'][] = '5 wins in a row';
        }
    }
}