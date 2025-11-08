<?php

namespace App\Core;

class AchievementSystem
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
        if (!array_key_exists('achievements', $_SESSION)) {
            $_SESSION['achievements'] = [];
        }
    }

    public function check(array $stats): void
    {
        if ($stats['wins'] >= 10 && !in_array('10 wins total', $_SESSION['achievements'])) {
            $_SESSION['achievements'][] = '10 wins total';
        }

        if ($stats['max_streak'] >= 5 && !in_array('5 wins in a row', $_SESSION['achievements'])) {
            $_SESSION['achievements'][] = '5 wins in a row';
        }
    }

    public function get(): array
    {
        return $this->session->get('achievements');
    }
}
