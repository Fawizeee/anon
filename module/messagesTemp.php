<?php 
use Symfony\Component\Routing\Generator\UrlGenerator;


class Templates{
public  $data = [];
public $isUser  = '';
private $query;
private $nameParam;
public $db;
public $id_string;

   public function __construct(mysqli $db) {
      $this->db = $db;
      $this->query =  $_SERVER["QUERY_STRING"]; 
      parse_str($this->query,$queryparams);
      $this->nameParam = reset($queryparams); 

   }
   public function checkUser(){
$stmt = $this->db->prepare("SELECT * FROM INFO WHERE USERNAME=?");
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


$msg_URl="$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]/anon/msg?name=".$this->nameParam;
$view_URL = "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]/anon/messages?name=".$this->nameParam;

$id = false;
if(isset($_SESSION["loggedin"])&&$_SESSION["loggedin"]){
      $id = true;
    if($_SESSION["name"]!==$this->nameParam)
        $id= false;

}



$dashboarddata = ["msgURL"=>$msg_URl ,"viewURL"=>$view_URL,"id"=>$this->nameParam,"isUser"=>$id];
return $dashboarddata;
    }
    public function reaction(){
        
 //$cookie_mod->Make_ckie("username",$this->nameParam);

if(isset($_SERVER["QUERY_STRING"])){
  


             //DATABASE CODE FOR QUERY
             $sql ="SELECT *
              FROM MESSAGES
              WHERE USERNAME = ?
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
              
                 include_once("../module/reactioui.php");
                 
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


