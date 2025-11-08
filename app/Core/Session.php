<?php

namespace App\Core;

use DateTime;

class Session
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!array_key_exists('stats', $_SESSION)) {
            $_SESSION['stats'] = [
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'streak' => 0,
                'max_streak' => 0,
            ];
        }

        if (!array_key_exists('games', $_SESSION)) {
            $_SESSION['games'][] = [
                'time' => 0,
                'result' => 0,
            ];
        }
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function getGames(int $count = 10): array
    {

        $games = array_reverse($this->get('games'));
        array_pop($games);

        return array_slice($games, 0, $count);
    }

    public function updateStats(string $result): void
    {
        $stats = $_SESSION['stats'];
        match ($result) {
            'win' => $stats['wins']++ && $stats['streak']++,
            'draw' => $stats['draws']++,
            'lose' => $stats['losses']++ && $stats['streak'] = 0,
        };
        $stats['max_streak'] = max($stats['max_streak'], $stats['streak']);
        $_SESSION['stats'] = $stats;
    }

    public function updateGames(string $result): void
    {
        $games = $_SESSION['games'];

        $time = new DateTime();
        $time = $time->format('Y-m-d H:i:s');

        $games[] = [
            'time' => $time,
            'result' => $result,
        ];

        $_SESSION['games'] = $games;
    }
}
