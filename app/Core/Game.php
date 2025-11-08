<?php

namespace App\Core;

class Game
{
    private Session $session;
    private Leaderboard $leaderboard;
    private AchievementSystem $achievements;

    private array $options = ['rock', 'paper', 'scissors'];

    public function __construct(Session $session, Leaderboard $leaderboard, AchievementSystem $achievements)
    {
        $this->session = $session;
        $this->leaderboard = $leaderboard;
        $this->achievements = $achievements;
    }

    public function play(string $playerChoice): string
    {

        $computerChoice = $this->options[mt_rand(0, 2)];

        $result = $this->determineWinner($playerChoice, $computerChoice);

        $this->session->updateStats($result);
        $this->session->updateGames($result);

        $this->achievements->check($this->session->get('stats'));


        $this->session->set('last_game', [
            'player' => $playerChoice,
            'computer' => $computerChoice,
            'result' => $result
        ]);

        return $result;
    }

    private function determineWinner(string $player, string $computer): string
    {
        if ($player === $computer) {
            return 'draw';
        }

        return match ($player) {
            'rock' => ($computer === 'scissors') ? 'win' : 'lose',
            'paper' => ($computer === 'rock') ? 'win' : 'lose',
            'scissors' => ($computer === 'paper') ? 'win' : 'lose',
        };
    }
}
