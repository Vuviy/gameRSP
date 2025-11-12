<?php

namespace App\Core;

use App\Base\CheckerRegistry;
use App\Base\Container;
use App\Core\Achievement\FiveWinsStreakChecker;
use App\Core\Achivment\TenWinsChecker;

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

    public function check(): void
    {

        $container = Container::getInstance();

        $registry =  $container->get(CheckerRegistry::class);
        $games = $this->session->get('games');

        $achievementsList = achievement_config();

        $chain = $registry->buildChain($achievementsList);
        $chain->handle($games);
    }

    public function get(): array
    {
        return $this->session->get('achievements');
    }
}
