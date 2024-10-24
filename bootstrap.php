<?php
    require 'vendor\autoload.php';
    use Anon\Database\Connection;
    use Anon\Src\AddSession_cookie;
    $db = new Connection();
    $userid = (new AddSession_cookie())->add();
