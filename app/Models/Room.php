<?php

namespace App\Models;

class Room
{
    public $id;
    public $players;
    public $type;

    const NORMAL = 'normal';
    const RANKED = 'ranked';
    const CUSTOM = 'custom';

    public function __construct($user, $type){
        $this->id = hash("md5", $user->id . rand(0, 1000));
        $this->players = [];
        $this->attachPlayer($user);
        $this->type = $type;
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

    public function detachPlayer($user){
        unset($this->players[$user]);
        $user->roomId = null;

        return $this->players;
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
            "players" => $players,
            "type" => $this->type
        ];
    }
}
