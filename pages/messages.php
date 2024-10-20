
<?php
require_once("../module/messagesTemp.php");
require_once("../module/connection.php");
require_once("../module/cookie.php");
require_once("../module/idcookiemaker.php");
require_once("../module/handlebarsTemplate.php");
session_start();

$mesgtemplateString = file_get_contents("../public/views/messages.hbs");
$handlebars = new HandlebarTemplate($mesgtemplateString);
$ReactiontemplateString = file_get_contents("../public/views/reaction.hbs");
$dashboardTemplateString = file_get_contents("../public/views/dashboard.hbs");
$handlebars->registerPartials("dashboard",$dashboardTemplateString);
$handlebars->registerPartials("reaction",$ReactiontemplateString);
$handlebars->registerPartials("nav",file_get_contents(filename:"../public/views/nav.hbs"));
$handlebars->registerHelpers("format");

if(isset($_COOKIE["remember"])&&!isset($_SESSION["loggedin"])){
    header("location:/anon/login");
    exit;
    }

$db= new DbConn();
$db = $db->db;

$tempClass = new Templates($db);


$tempClass->checkUser();
$Adata = [... $tempClass->dashboard(),"message"=>[...$tempClass->reaction()["message"]],"isUser"=>$tempClass->dashboard()["isUser"]];

echo $handlebars->render($Adata);
               



          