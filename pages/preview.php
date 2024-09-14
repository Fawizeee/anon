
<?php
include_once("../module/connection.php");
include_once "../module/handlebarsTemplate.php";


session_start();

if (isset($_GET["id"])) {
  
    $_SESSION["id"] = $_GET["id"];
    $_SESSION["userid"] = $_GET["userid"];
    $_SESSION["multi"] =intval($_GET["multi"]) ;
 

    session_write_close();
   
  
}
else{
require dirname(__DIR__) . '\vendor\autoload.php';
if(!isset($_SESSION["id"])){
    http_response_code(403);
    header("location:/anon/403");
}
$db = new DbConn();
$db = $db->conn();
if (!$db) {
    // handle database connection error
    echo "Database connection failed";
    exit;
}

$id = $_SESSION["id"];
$userid =     $_SESSION["userid"];
$multi =   $_SESSION["multi"];

// use prepared statement to prevent SQL injection
$stmt;
if($multi){
$stmt = $db->prepare("SELECT * FROM MESSAGES WHERE SENDERID = ? AND USERID= ?");
$stmt->bind_Param("ss", $id,$userid);


}
else{
$stmt = $db->prepare("SELECT * FROM MESSAGES WHERE  MSGID = ?");
$stmt->bind_Param("s", $id);
}

$retexe =$stmt->execute();
     $ret = $stmt->get_result();
// fetch all rows

include_once("../module/reactioui.php");

    while ($row = $ret->fetch_array(SQLITE3_ASSOC)) {
      
    $reactions = new getReactionUIData($row["USERID"], $row["SENDERID"], $row["MSGID"], ["FUNNY" => $row["FUNNY"], "SAD" => $row["SAD"], "BORING" => $row["BORING"], "CRAZY" => $row["CRAZY"]]);
    $reactlist = $reactions->getReactionUIData();
    $data = [...$row, "reactlist" => $reactlist,"preview"=>true];
    // use a template engine to render the HTML template
    $handlebars= new HandlebarTemplate(file_get_contents("views/reaction.hbs"));
 
     $template = $handlebars->compile();
    echo $handlebars->render( $data);
}}