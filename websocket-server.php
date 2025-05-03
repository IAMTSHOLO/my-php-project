<?php
require dirname(__DIR__) . '/vendor/autoload.php'; // Make sure the path is correct based on your setup

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\WebSocket\WsProtocol;
use Ratchet\ConnectionInterface as ConnInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";
        $this->clients->attach($conn);
    }

    public function onMessage(ConnInterface $from, $msg) {
        echo "Message received: {$msg}\n";
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnInterface $conn) {
        echo "Connection {$conn->resourceId} has disconnected\n";
        $this->clients->detach($conn);
    }

    public function onError(ConnInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Start WebSocket server
$server = IoServer::factory(
    new WsServer(
        new ChatServer()
    ),
    8080
);

$server->run();
