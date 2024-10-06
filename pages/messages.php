
<?php
require_once("../module/messagesTemp.php");
require_once("../module/connection.php");
require_once("../module/cookie.php");
require_once("../module/idcookiemaker.php");
require_once("../module/handlebarsTemplate.php");

$mesgtemplateString = file_get_contents("views/messages.hbs");
$handlebars = new HandlebarTemplate($mesgtemplateString);
$ReactiontemplateString = file_get_contents("views/reaction.hbs");
$dashboardTemplateString = file_get_contents("views/dashboard.hbs");
$handlebars->registerPartials("dashboard",$dashboardTemplateString);
$handlebars->registerPartials("reaction",$ReactiontemplateString);

session_start();

var_dump([$_SESSION]);
$db= new DbConn();
$db = $db->db;

$tempClass = new Templates($db);


$tempClass->checkUser();
$Adata = [... $tempClass->dashboard(),"message"=>[...$tempClass->reaction()["message"]],"isUser"=>$tempClass->dashboard()["isUser"]];

echo $handlebars->render($Adata);
               

            

          