<?php

namespace App\Game;

class Room
{
    public $id;
    public $players;
    public $game;

    public function __construct($user){
        $this->id = hash("md5", $user->id . rand(0, 1000));
        $this->players = [];
        $this->attachPlayer($user);
    }

    public function attachPlayer($user){
        if(count($this->players) >= 0 && count($this->players) < 2){
            if($user->roomId != null){
                throw new \Exception('Player already in another room');
            }

            $user->roomId = $this->id;

            $this->players[] = $user;
            return;
        }

        throw new \Exception('Room is already full');
    }

    public function startGame($type){
        if(count($this->players) < 2){
            throw new \Exception('Room is not full');
        }

        $this->game = new Game($this->players, $type);
        $this->game->start();

        return $this->game;
    }

    public function endTurn($gameData){
        $this->game->endTurn($gameData);

        return $this->game;
    }

    public function toArray(){
        $players = [];

        foreach($this->players as $player){
            $formatedPlayer = (object)[
                "id" => $player->id,
                "name" => $player->name,
                "email" => $player->email,
                "roomId" => $player->roomId
            ];            
            $players[] = $formatedPlayer;
        }

        return [
            "id" => $this->id,
            "players" => $players
        ];
    }

    public function sendMessageToRoom($message){
        foreach($this->players as $player){
            $player->conn->send(json_encode($message));
        }
    }
}
