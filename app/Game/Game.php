<?php

namespace App\Game;

class Game
{
    public $player1;
    public $player2;
    public $turn;
    public $type;
    public $history;
    public $ended;
    public $gameWinner;

    const PLAYING = 'playing';
    const WAITING = 'waiting';

    const ROCK = 'rock';
    const PAPER = 'paper';
    const SCISSOR = 'scissor';

    const NORMAL = 'normal';
    const RANKED = 'ranked';
    const CUSTOM = 'custom';

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
        $this->history = [];
        $this->ended = false;
        $this->gameWinner = null;
    }

    public function start(){
        $this->turn = 1;
    }

    public function endTurn($gameData){
        if($gameData->player1->info->id == $this->player1->info->id){
            $this->player1->choice = $gameData->player1->choice;
            $this->player1->state = self::WAITING;

            if($this->player2->state == self::WAITING){
                $this->chooseTurnOrGameWinner();
            }
        }else{
            $this->player2->choice = $gameData->player1->choice;
            $this->player2->state = self::WAITING;

            if($this->player1->state == self::WAITING){
                $this->chooseTurnOrGameWinner();
            }
        }
    }

    public function chooseTurnOrGameWinner(){
        $this->history[$this->turn] = (object)[
            "player1" => clone $this->player1,
            "player2" => clone $this->player2
        ];

        if($this->player1->choice == self::ROCK){
            if($this->player2->choice == self::ROCK){
                $this->history[$this->turn]->{"winner"} = "draw";

                $this->turn ++;
            }else if($this->player2->choice == self::PAPER){
                $this->history[$this->turn]->{"winner"} = $this->player2->info->name;

                $this->player2->score++;

                if($this->player2->score >= 3){
                    $this->end($this->player2);
                }
                
                $this->turn++;
            }else if($this->player2->choice == self::SCISSOR){
                $this->history[$this->turn]->{"winner"} = $this->player1->info->name;

                $this->player1->score++;

                if($this->player1->score >= 3){
                    $this->end($this->player1);
                }

                $this->turn++;
            }
        }else if($this->player1->choice == self::PAPER){
            if($this->player2->choice == self::ROCK){
                $this->history[$this->turn]->{"winner"} = $this->player1->info->name;

                $this->player1->score++;

                if($this->player1->score >= 3){
                    $this->end($this->player1);
                }

                $this->turn++;
            }else if($this->player2->choice == self::PAPER){
                $this->history[$this->turn]->{"winner"} = "draw";
                
                $this->turn ++;
            }else if($this->player2->choice == self::SCISSOR){
                $this->history[$this->turn]->{"winner"} = $this->player2->info->name;

                $this->player2->score++;

                if($this->player2->score >= 3){
                    $this->end($this->player2);
                }

                $this->turn++;
            }
        }else if($this->player1->choice == self::SCISSOR){
            if($this->player2->choice == self::ROCK){
                $this->history[$this->turn]->{"winner"} = $this->player2->info->name;

                $this->player2->score++;

                if($this->player2->score >= 3){
                    $this->end($this->player2);
                }

                $this->turn++;
            }else if($this->player2->choice == self::PAPER){
                $this->history[$this->turn]->{"winner"} = $this->player1->info->name;

                $this->player1->score++;

                if($this->player1->score >= 3){
                    $this->end($this->player1);
                }

                $this->turn++;
            }else if($this->player2->choice == self::SCISSOR){
                $this->history[$this->turn]->{"winner"} = "draw";

                $this->turn ++;
            }
        }

        $this->resetBothPlayersChoiceAndState();
    }

    public function end($player){
        $this->gameWinner = $player;
        $this->ended = true;
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

    private function resetBothPlayersChoiceAndState(){
        $this->player1->choice = null;
        $this->player2->choice = null;

        $this->player1->state = self::PLAYING;
        $this->player2->state = self::PLAYING;
    }
}