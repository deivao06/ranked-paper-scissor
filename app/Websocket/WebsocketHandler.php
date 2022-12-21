<?php

namespace App\Websocket;

use App\Http\Repositories\UserRepository;
use App\Models\Room;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebsocketHandler implements MessageComponentInterface {
    protected $playerQueue;
    protected $rooms;
    private $userRepository;

    public function __construct() {
        $this->playerQueue = new \SplObjectStorage;
        $this->userRepository = new UserRepository();
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "Connection {$conn->remoteAddress}|{$conn->resourceId} has started queue\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
        $command = $data->command;

        switch($command){
            case 'queue':
                if($data->queue == 'normal'){
                    $user = (object) $this->userRepository->getUserByEmail($data->email)->toArray();
                    $user->{'roomId'} = null;
                    $user->{'conn'} = $from;

                    $this->playerQueue->attach($user);
    
                    if(count($this->rooms) == 0){
                        $room = new Room($user, Room::NORMAL);
                        $response = [
                            "room" => $room->toArray(),
                            "message" => "Match found",
                            "command" => null
                        ];

                        $this->rooms[] = $room;
    
                        $from->send(json_encode($response));
                    }else{
                        $roomFound = false;
                        foreach($this->rooms as $room){
                            if(count($room->players) < 2){
                                $room->attachPlayer($user);
                                $roomFound = true;

                                $response = [
                                    "room" => $room->toArray(),
                                    "message" => "Player found",
                                    "command" => null
                                ];

                                $this->sendMessageToRoom($user->roomId, $response);
                                break;
                            }
                        }

                        if(!$roomFound){
                            $room = new Room($user, Room::NORMAL);
                            $response = [
                                "room" => $room->toArray(),
                                "message" => "Match found",
                                "command" => null
                            ];
                            $this->rooms[] = $room;

                            $from->send(json_encode($response));
                        }
                    }
                }
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        foreach($this->playerQueue as $player){
            if($player->conn == $conn){
                foreach($this->rooms as $room){
                    if($player->roomId == $room->id){
                        $response = [
                            "room" => $room->toArray(),
                            "message" => "Room closed",
                            "command" => 'exit-room'
                        ];

                        foreach($room->players as $player){
                            $player->conn->send(json_encode($response));
                        }

                        $this->removeRoom($room);
                    }
                }
            }
        }

        echo "Connection {$conn->remoteAddress}|{$conn->resourceId} exit from room\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        
        $conn->close();
    }

    private function sendMessageToRoom($roomId, $message){
        foreach($this->playerQueue as $player){
            if($player->roomId == $roomId){
                $player->conn->send(json_encode($message));
            }
        }
    }

    private function removeRoom(Room $room){
        $rooms = [];
        foreach($this->rooms as $value){
            if($room->id != $value->id){
                $rooms[] = $value;
            }
        }

        $this->rooms = $rooms;
    }
}