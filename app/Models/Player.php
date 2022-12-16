<?php

namespace App\Models;

class Player
{
    public $ip;
    public $resourceId;
    public $conn;
    public $roomId;

    public function __construct($conn){
        $this->ip = $conn->remoteAddress;
        $this->resourceId = $conn->resourceId;
        $this->conn = $conn;
        $this->roomId = null;
    }

    public function toArray(){
        return [
            "ip" => $this->ip,
            "resourceId" => $this->resourceId,            
            "roomId" => $this->roomId
        ];
    }
}
