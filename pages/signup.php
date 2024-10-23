<?php

require_once '../bootstrap.php';

use Anon\Src\{Password_mod,UserRegister,HandlebarTemplate};
// use Cradle\Handlebars\HandlebarsHandler as Handlebars;
// require dirname(__DIR__) . '\vendor\autoload.php';
// database connection 

$password_mod = new Password_mod();
$userRegister = new UserRegister($db,$password_mod);
$handlebars = new HandlebarTemplate(file_get_contents("../public/views/signup.hbs"));
$messageCon = null;
$status = null;

try{
    if (isset($_POST["name"] )&&isset($_POST["pword"])) {
        $name =(string) strtolower($_POST["name"]);
         $pword = (string) $_POST["pword"];
       $userSaved = $userRegister->registerUser($name, $pword);
     
     if($userSaved){
        $messageCon = "You have been succesfully registered";
        $status = "success";
     }
     else{
        throw new Exception("Error occured while registering crestering credentials");
     }
}
}
catch(Exception $e){
    $messageCon = $e->getMessage();
    $status = "fail";
    

}finally{
    $data = ["message" => $messageCon,"status"=>$status];
  $handlebars->registerPartials("nav",file_get_contents(filename:"../public/views/nav.hbs"));

    echo $handlebars->render($data);
    $db->db->close();
}
