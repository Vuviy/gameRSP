
<?php

namespace App\Base;


use App\Base\Interface\AchievementCheckerInterface;

abstract class AbstractAchievementChecker implements AchievementCheckerInterface
{
    protected ?AchievementCheckerInterface $next = null;

    public function setNext(AchievementCheckerInterface $next): AchievementCheckerInterface
    {
        $this->next = $next;
        return $next;
    }

    public function check(array $games): void
    {
        $this->handle($games);

        if ($this->next) {
            $this->next->check($games);
        }
    }

    abstract public function handle(array $games): void;
}
