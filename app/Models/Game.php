<?php

namespace App\Models;

class Game
{
    public $players;
    public $turn;

    const PLAYING = 'playing';
    const WAITING = 'waiting';

    public function __construct($players){
        foreach($players as $player){
            $this->players[] = (object)[
                "data" => $player,
                "state" => self::PLAYING,
                "choice" => null
            ];
        }
    }

    public function start(){
        $this->turn == 1;
    }

    public function endTurn(){
        foreach($this->players as $player){
            $player->state = self::PLAYING;
            $player->choice = null;
        }

        $this->turn++;
    }

    public function end(){

    }

    public function changePlayerState($player){
        if(isset($this->players[$player])){
            if($this->players[$player]->state == self::PLAYING && $this->players[$player]->choice != null){
                $this->players[$player]->state = self::WAITING;
            }else{
                $this->players[$player]->state = self::PLAYING;
            }
        }
    }
}