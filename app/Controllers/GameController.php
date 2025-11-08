<?php

namespace App\Controllers;

use App\Base\Container;
use App\Base\ViewFactory;
use App\Core\Game;
use App\Core\Session;
use App\Core\Leaderboard;
use App\Core\AchievementSystem;

class GameController
{
    private Game $game;
    private Session $session;
    private Leaderboard $leaderboard;
    private AchievementSystem $achievements;

    public function __construct()
    {
        $this->session = new Session();
        $this->leaderboard = new Leaderboard($this->session);
        $this->achievements = new AchievementSystem($this->session);
        $this->game = new Game($this->session, $this->leaderboard, $this->achievements);
    }

    public function index(): void
    {
        $stats = $_SESSION['stats'] ?? [
            'wins' => 0,
            'losses' => 0,
            'draws' => 0,
            'streak' => 0,
            'achievements' => [],
        ];

        $lastResult = $_SESSION['last_game'] ?? null;

        $achievements = $this->achievements->get();

        $games = $this->session->getGames();

        $leaders = $this->leaderboard->getLeaders();


        $data = [
            'stats' => $stats,
            'lastResult' => $lastResult,
            'achievements' => $achievements,
            'leaders' => $leaders,
            'games' => $games,
        ];

        $container = Container::getInstance();
        $viewFactory = $container->get(ViewFactory::class);
        $view = $viewFactory->make('game');
        $view->render($data);

    }

    public function play()
    {
        $res = '';
        $choice = $_POST['choice'] ?? null;
        if ($choice) {
           $res = $this->game->play($choice);
        }
        header('Content-Type: application/json; charset=utf-8', true, 201);
        echo json_encode($res);
        exit;
    }
}
