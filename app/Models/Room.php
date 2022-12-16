<?php

namespace App\Models;

class Room
{
    public $id;
    public $players;

    public function __construct(Player $player){
        $this->id = hash("md5", $player->conn->resourceId . rand(0, 1000));
        $this->players = [];
        $this->attachPlayer($player);
    }

    public function attachPlayer(Player $player){
        if(count($this->players) >= 0 && count($this->players) < 2){
            if($player->roomId != null){
                throw new \Exception('Player already in another room');
            }

            $player->roomId = $this->id;
            $this->players[] = $player;

            return;
        }

        throw new \Exception('Room is already full');
    }

    public function detachPlayer(Player $player){
        unset($this->players[$player]);

        return $this->players;
    }

    public function toArray(){
        return [
            "id" => $this->id,
            "players" => $this->players
        ];
    }
}
