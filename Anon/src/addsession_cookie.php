<?php

namespace Anon\Src;


session_start();
 class AddSession_cookie{
protected ?string $id = null;
    public function __construct(){
     
    }
    public function add(){
            $cookie_mod = new cookie_mod();
    $cookie_id = new idcookie();

$id_string = $_SESSION['userid']??$_COOKIE['id']??uniqid(mt_rand(10,10));
$_SESSION['userid'] = $id_string;
$cookie_mod->Make_ckie( ["id"=>$id_string]);
return  $id_string;
    }
 }

