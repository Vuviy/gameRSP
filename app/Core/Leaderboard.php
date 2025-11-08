<?php

namespace App\Core;

class Leaderboard
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getLeaders(): array
    {
        $games = $this->session->get('games');

        if([] === $games) {
            return [];
        }

        $res = [
            'player' => 0,
            'computer' => 0,
        ];

        foreach ($games as $game) {

            if($game['result'] === 'win') {
                $res['player'] ++;
            } else{
                $res['computer'] ++;
            }

        }
        arsort($res);
        return $res;
    }
}
