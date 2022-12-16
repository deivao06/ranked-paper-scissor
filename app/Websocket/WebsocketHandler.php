<?php

namespace App\Websocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebsocketHandler implements MessageComponentInterface {
    protected $playerQueue;

    public function __construct() {
        $this->playerQueue = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->playerQueue->attach($conn);
        echo "Connection {$conn->remoteAddress}|{$conn->resourceId} has started queue\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->playerQueue->detach($conn);

        echo "Connection {$conn->remoteAddress}|{$conn->resourceId} exit from queue\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        
        $conn->close();
    }
}