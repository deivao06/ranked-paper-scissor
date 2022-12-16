<?php

namespace App\Websocket;

use App\Models\Player;
use App\Models\Room;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebsocketHandler implements MessageComponentInterface {
    protected $clients;
    protected $rooms;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $player = new Player($conn);
        $this->clients->attach($player);

        if(count($this->rooms) == 0){
            $room = new Room($player);
            $this->rooms[$room->id] = $room;
        }

        $player->conn->send(json_encode($player->toArray()));

        echo "Connection {$player->ip}|{$player->resourceId} has connected\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $player = $this->findPlayerByConn($conn);
        
        if($player instanceof Player){
            $this->clients->detach($player);
        }else{
            throw new \Exception("Failed to find player");
        }

        $room = $this->rooms[$player->roomId];
        $room->detachPlayer($player);

        if(count($room->players) <= 0){
            unset($this->rooms[$room]);
        }

        echo "Connection {$player->ip}|{$player->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $player = $this->findPlayerByConn($conn);
        
        if($player instanceof Player){
            $this->clients->detach($player);
        }else{
            throw new \Exception("Failed to find player");
        }

        echo "An error has occurred: {$e->getMessage()}\n";
        
        $player->conn->close();
    }

    private function findPlayerByConn(ConnectionInterface $conn){
        foreach ($this->clients as $player){
            if($player->ip == $conn->remoteAddress && $player->resourceId == $conn->resourceId){
                return $player;
            }
        }

        return false;
    }
}