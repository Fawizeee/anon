<?php 
    namespace Anon\Src;


class Templates{
public  $data = [];
public $isUser  = '';
private $query;
private $nameParam;
public $db;
public $id_string;

   public function __construct(\mysqli $db) {
      $this->db = $db;
      $this->query =  $_SERVER["QUERY_STRING"]; 
      parse_str($this->query,$queryparams);
      $this->nameParam = reset($queryparams); 
      $this->nameParam = $_GET["id"];

   }
   public function checkUser(){
$stmt = $this->db->prepare("SELECT * FROM INFO WHERE ID=?");
$stmt->bind_Param("s",$this->nameParam);
 $stmt->execute();
 $result = $stmt->get_result();



if (empty($result->fetch_array(SQLITE3_ASSOC))){ 
 http_response_code(404);

 header("location:/anon/404");
 exit;
  }
  else{
  $cookie_mod = new cookie_mod();
  $id = new idcookie();
  $this->id_string = null;
  
  //$cookie_mod->chk_cookie("../login.php");
  if(!isset($_SESSION["userid"])){
             
    $this->id_string = $id->makeidcookie();
   //  $cookie_mod->Make_ckie(["userid"=>$this->id_string]);
    $_SESSION["userid"] = $this->id_string;
       
   } 
}
   }
    public function dashboard(){
$msg_URl = null;
$view_URL = null;
$dashboarddata = null;

$id = false;
if(isset($_SESSION["loggedin"])&&$_SESSION["loggedin"]){
      $id = true;
      $msg_URl="$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]/anon/msg?name=".$_SESSION["name"];
      $view_URL = "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]/anon/messages?id=".$_SESSION["userid"];

      if($_SESSION["userid"]!==$this->nameParam)
      $id= false;
   
}

$dashboarddata = ["msgURL"=>$msg_URl ,"viewURL"=>$view_URL,"id"=>$_SESSION["name"]??null,"isUser"=>$id];
return $dashboarddata;


    }
    public function reaction(){
        
 //$cookie_mod->Make_ckie("username",$this->nameParam);

if(isset($_SERVER["QUERY_STRING"])){
  


             //DATABASE CODE FOR QUERY
             $sql ="SELECT *
              FROM MESSAGES
              WHERE USERID = ?
             ";
             //

             // Query Execution
              $stmt = $this->db->prepare($sql);
              $stmt->bind_Param("s",$this->nameParam);
              $stmt->execute();
              $ret = $stmt->get_result();
              //
              // get data for Reaction
               if($ret){
              $this->id_string =   isset($_SESSION["userid"])?$_SESSION["userid"]:$this->id_string;
              
                 
                while($row  = $ret->fetch_array(SQLITE3_ASSOC)){

                    $rowData = ["FUNNY"=>$row["FUNNY"],"SAD"=>$row["SAD"],"BORING"=>$row["BORING"],"CRAZY"=>$row["CRAZY"]];
          
$reactions = new getReactionUIData($row["USERID"],$this->id_string,$row["MSGID"],$rowData); 
$reactlist = $reactions->getReactionUIData();
array_push($this->data, [...$row,"reactlist"=>$reactlist]);
                    
    } 
   
          }
      }
 return["message"=>$this->data,"isUser"=> $this->isUser];
      }
}


