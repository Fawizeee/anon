
<?php

require_once '../bootstrap.php';  
use Anon\Src\{HandlebarTemplate,getReactionUIData};
(array) $data = [];
if (isset($_GET["id"])) {
  
    $_SESSION["id"] = $_GET["id"];
    $_SESSION["user"] = $_GET["userid"];
    $_SESSION["multi"] =intval($_GET["multi"]) ;
 

    session_write_close();
   
  
}
else{
require dirname(__DIR__) . '\vendor\autoload.php';


$db = $db->conn();
if (!$db) {
    // handle database connection error
    echo "Database connection failed";
    exit;
}
if(!isset($_GET["selectid"])){
$id = $_SESSION["id"];
$user =     $_SESSION["user"];
$multi =   $_SESSION["multi"];

// use prepared statement to prevent SQL injection
$stmt;
if($multi){
$stmt = $db->prepare("SELECT * FROM MESSAGES WHERE SENDERID = ? AND USERID= ?");
$stmt->bind_Param("ss", $id,$user);


}
else if(!$multi){
$stmt = $db->prepare("SELECT * FROM MESSAGES WHERE  MSGID = ?");
$stmt->bind_Param("s", $id);
}

$retexe =$stmt->execute();
     $ret = $stmt->get_result();
// fetch all rows
while ($row = $ret->fetch_array(SQLITE3_ASSOC)) {
  $reactions = new getReactionUIData($row["USERID"], $row["SENDERID"], $row["MSGID"], ["FUNNY" => $row["FUNNY"], "SAD" => $row["SAD"], "BORING" => $row["BORING"], "CRAZY" => $row["CRAZY"]]);
  $reactlist = $reactions->getReactionUIData();
  $data [] = [...$row, "reactlist" => $reactlist];
  // use a template engine to render the HTML template

}

}

else if(isset($_GET)&&isset($_GET["selectid"])){
    $selectedid = (string)$_GET["selectid"];
    $query = "SELECT * FROM MESSAGES WHERE MSGID IN (SELECT SELECTID FROM SELECTTABLE WHERE ID =?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s",$selectedid);
    $retexe =$stmt->execute();
    $ret = $stmt->get_result();


    while ($row = $ret->fetch_array(SQLITE3_ASSOC)) { 
      $reactions = new getReactionUIData($row["USERID"], $row["SENDERID"], $row["MSGID"], ["FUNNY" => $row["FUNNY"], "SAD" => $row["SAD"], "BORING" => $row["BORING"], "CRAZY" => $row["CRAZY"]]);
      $reactlist = $reactions->getReactionUIData();
      $data []= [...$row, "reactlist" => $reactlist,];

  }
  
    }}
    $PreviewtemplateString = file_get_contents("../public/views/preview.hbs");
    $handlebars = new HandlebarTemplate($PreviewtemplateString);
    $ReactiontemplateString = file_get_contents("../public/views/reaction.hbs");
    $handlebars->registerPartials("reaction",$ReactiontemplateString);
    $handlebars->registerHelpers("format");    

     $template = $handlebars->compile();
   
    echo $handlebars->render( ["message"=>$data]);
