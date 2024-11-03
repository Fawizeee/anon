<?php
    require 'vendor\autoload.php';
    use Anon\Database\Connection;
    use Anon\Src\AddSession_cookie;
    $db = new Connection();
    $userid = (new AddSession_cookie())->add();
    header('Content-Type:text/html');
    header('Access-Control-Allow-Origin:localhost');
 