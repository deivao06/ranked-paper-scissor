<?php

namespace App\Websocket;

use App\Http\Repositories\UserRepository;
use App\Game\Room;
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
                $user = (object) $this->userRepository->getUserByEmail($data->email)->toArray();
                $user->{'roomId'} = null;
                $user->{'conn'} = $from;
    
                $this->playerQueue->attach($user);
    
                if(count($this->rooms) == 0){
                    $room = new Room($user);
    
                    $response = [
                        "room" => $room->toArray(),
                        "command" => 'room-created'
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
                                "command" => 'room-found'
                            ];
    
                            $from->send(json_encode($response));
                            break;
                        }
                    }
    
                    if(!$roomFound){
                        $room = new Room($user);
    
                        $response = [
                            "room" => $room->toArray(),
                            "command" => 'room-created'
                        ];
    
                        $this->rooms[] = $room;
    
                        $from->send(json_encode($response));
                    }
                }
            break;
            case 'start-game':
                $player = $this->getPlayerByConn($from);
                $room = $this->getRoomById($player->roomId);
                $game = $room->startGame($data->queue);

                foreach($room->players as $player){
                    $response = [
                        "game" => $this->formatGame($player, $game),
                        "command" => 'game-started'
                    ];
                    
                    $player->conn->send(json_encode($response));
                }
            break;
            case 'end-turn':
                $player = $this->getPlayerByConn($from);
                $room = $this->getRoomById($player->roomId);
                $game = $room->endTurn($data->game);
                
                foreach($room->players as $player){
                    $formatedGame = $this->formatGame($player, $game);

                    $response = [
                        "game" => $formatedGame,
                        "command" => 'game-update'
                    ];

                    if($formatedGame->ended){
                        $response["command"] = 'game-ended';
                    }
                    
                    $player->conn->send(json_encode($response));
                }
            break;
            case 'timeout':
                $player = $this->getPlayerByConn($from);
                $room = $this->getRoomById($player->roomId);

                if($room && !$room->game->ended){
                    if($player->id == $room->game->player1->info->id){
                        $room->game->chooseTurnOrGameWinner($room->game->player2);
                    }else{
                        $room->game->chooseTurnOrGameWinner($room->game->player1);
                    }

                    foreach($room->players as $player){
                        $formatedGame = $this->formatGame($player, $room->game);
    
                        $response = [
                            "game" => $formatedGame,
                            "command" => 'game-update'
                        ];
    
                        if($formatedGame->ended){
                            $response["command"] = 'game-ended';
                        }
                        
                        $player->conn->send(json_encode($response));
                    }
                }
            break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $player = $this->getPlayerByConn($conn);
        $room = $this->getRoomById($player->roomId);
        
        if($room){
            if($room->game){
                if($player->id == $room->game->player1->info->id){
                    $room->game->end($room->game->player2);
                }else{
                    $room->game->end($room->game->player1);
                }
    
                $formatedGame = $this->formatGame($player, $room->game);
    
                $response = [
                    "game" => $formatedGame,
                    "command" => 'game-ended'
                ];

                $room->sendMessageToRoom($response);
            }

            $this->removeRoom($room);
        }
        
        echo "Connection {$conn->remoteAddress}|{$conn->resourceId} exit from room\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        
        $conn->close();
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

    private function getPlayerByConn(ConnectionInterface $conn){
        foreach($this->playerQueue as $player){
            if($player->conn == $conn){
                return $player;
            }
        }
    }

    private function getRoomById($roomId){
        foreach($this->rooms as $room){
            if($room->id == $roomId){
                return $room;
            }
        }
    }

    private function formatGame($player, $game){
        $formatedGame = (object) [
            "player1" => $game->player1,
            "player2" => $game->player2,
            "turn" => $game->turn,
            "type" => $game->type,
            "history" => (object)$game->history,
            "ended" => $game->ended,
            "gameWinner" => $game->gameWinner,
        ];

        if($formatedGame->player1->info->id != $player->id){
            $formatedGame->player1 = $game->player2;
            $formatedGame->player2 = (object)[
                "info" => $game->player1->info,
                "state" => $game->player1->state,
                "score" => $game->player1->score
            ];
        }else{
            $formatedGame->player1 = $game->player1;
            $formatedGame->player2 = (object)[
                "info" => $game->player2->info,
                "state" => $game->player2->state,
                "score" => $game->player2->score
            ];
        }

        return $formatedGame;
    }
}