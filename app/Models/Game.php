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
        $this->turn == 1;
    }

    public function endTurn($gameData){
        dump($gameData);
        dump($this->player1, $this->player2);
        
        if($gameData->player1->info->id == $this->player1->info->id){
            $this->player1->choice = $gameData->player1->choice;
            $this->player1->state = self::WAITING;

            if($this->player2->state == self::WAITING){
                
            }

            return $this->game;
        }else{
            $this->player2->choice = $gameData->player1->choice;
            $this->player2->state = self::WAITING;

            if($this->player1->state == self::WAITING){
                
            }

            return $this->game;
        }
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

    public function playerWithoutChoice($player){
        return [
            "info" => $player->info,
            "state" => $player->state,
            "score" => $player->score,
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