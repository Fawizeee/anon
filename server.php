<?php
namespace MyApp;

require dirname(__DIR__) . '\anon\vendor\autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
include_once ("socket.php");
$Chat = new Chat();



 

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8080
    );
  echo 'yes';
    $server->run();