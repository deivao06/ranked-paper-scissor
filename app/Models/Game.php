<?php

namespace App\Models;

class Game
{
    public $player1;
    public $player2;
    public $turn;
    public $type;

    const PLAYING = 'playing';
    const WAITING = 'waiting';

    public function __construct($players, $type){
        $formatedPlayers = $this->formatPlayers($players);

        $this->player1 = (object)[
            "info" => $formatedPlayers[0],
            "state" => self::PLAYING,
            "choice" => null,
            "score" => 0
        ];

        $this->player2 = (object)[
            "info" => $formatedPlayers[1],
            "state" => self::PLAYING,
            "choice" => null,
            "score" => 0
        ];

        $this->type = $type;
    }

    public function start(){
        $this->turn = 1;
    }

    public function endTurn(){
        $this->player1->state = self::PLAYING;
        $this->player1->choice = null; 

        $this->player2->state = self::PLAYING;
        $this->player2->choice = null; 

        $this->turn++;
    }

    public function end(){

    }

    public function toArray(){
        return [
            "player1" => $this->player1,
            "player2" => $this->player2,
            "turn" => $this->turn,
            "type" => $this->type
        ];
    }

    private function formatPlayers($players){
        $formatedPlayers = [];

        foreach($players as $player){
            $player = (object)[
                "id" => $player->id,
                "name" => $player->name,
                "email" => $player->email,
                "roomId" => $player->roomId
            ];            
            $formatedPlayers[] = $player;
        }

        return $formatedPlayers;
    }
}