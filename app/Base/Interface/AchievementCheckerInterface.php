<?php

namespace App\Base\Interface;

interface AchievementCheckerInterface
{
    public function setNext(AchievementCheckerInterface $next): AchievementCheckerInterface;
    public function check(array $games): void;

}