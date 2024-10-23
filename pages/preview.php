
<?php

require_once '../bootstrap.php';  
use Anon\Src\{HandlebarTemplate,getReactionUIData};
session_start();
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
$userid =     $_SESSION["user"];
$multi =   $_SESSION["multi"];

// use prepared statement to prevent SQL injection
$stmt;
if($multi){
$stmt = $db->prepare("SELECT * FROM MESSAGES WHERE SENDERID = ? AND USERID= ?");
$stmt->bind_Param("ss", $id,$userid);


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
  $data = [...$row, "reactlist" => $reactlist,"preview"=>true];
  // use a template engine to render the HTML template
  $handlebars= new HandlebarTemplate(file_get_contents("../public/views/reaction.hbs"));
  $handlebars->registerHelpers("format");
  $handlebars->registerPartials("nav",file_get_contents(filename:"../public/views/nav.hbs"));
   $template = $handlebars->compile();
  echo $handlebars->render( $data);
}

}

else if(isset($_GET)&&isset($_GET["selectid"])){
    $selectedid = (string)$_GET["selectid"];
    $query = "SELECT * FROM MESSAGES WHERE MSGID IN (SELECT SELECTED FROM SELECTTABLE WHERE ID =?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s",$selectedid);
    $retexe =$stmt->execute();
    $ret = $stmt->get_result();

    while ($row = $ret->fetch_array(SQLITE3_ASSOC)) {
      $reactions = new getReactionUIData($row["USERID"], $row["SENDERID"], $row["MSGID"], ["FUNNY" => $row["FUNNY"], "SAD" => $row["SAD"], "BORING" => $row["BORING"], "CRAZY" => $row["CRAZY"]]);
      $reactlist = $reactions->getReactionUIData();
      $data = [...$row, "reactlist" => $reactlist,"preview"=>true];
      // use a template engine to render the HTML template
      $handlebars= new HandlebarTemplate(file_get_contents("../public/views/reaction.hbs"));
      $handlebars->registerHelpers("format");    

       $template = $handlebars->compile();
      echo $handlebars->render( $data);
  }
  
    }}


