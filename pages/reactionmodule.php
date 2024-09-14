
<?php
session_start();
if (!isset($_SERVER["HTTP_X_REQUESTED_WITH"])&& !$_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest"){
     http_response_code(400);
     header("location:/anon/400");
     exit;
}

include_once("../module/connection.php");
 class Reactionmodule{
       public $reaction_arr = [];
       protected $db;
       protected $dbconn;

       public function __construct(){
            $this->db =new DbConn();
            $this->dbconn = $this->db->db;
            $this->reaction_arr ;
           
       }

       function get_reaction($msgid){

 $stmt = $this->dbconn->prepare("SELECT FUNNY,SAD,BORING,CRAZY,MSGID FROM MESSAGES WHERE MSGID = ?");;
 $stmt->bind_Param("s",$msgid);
 $stmt_exe = $stmt->execute();
 $ret = $stmt ->get_result();

          if($stmt_exe){
while($row  = $ret->fetch_array(SQLITE3_ASSOC)){
     $this->reaction_arr["FUNNY"] = $row["FUNNY"];
     $this->reaction_arr["SAD"] = $row["SAD"];
     $this->reaction_arr["BORING"]= $row["BORING"];
     $this->reaction_arr["CRAZY"]= $row["CRAZY"];
 
         
       }
     }
       return $this->reaction_arr;
 }
 function updateRctn($reaction,$reactionARR,$user){     
      $sql = "UPDATE MESSAGES
      SET $reaction= ?
      WHERE
      MSGID = ?
      ";
  $stmt = $this->dbconn->prepare($sql);
  $stmt->bind_param("ss",$reactionARR,$user);
  $stmt->execute();
}


}
 ?>  
 <?php 
if(isset($_GET)){
  

    if(isset($_GET["uname"])&&isset($_GET["react"])&&isset($_GET["cuser"])&&isset($_GET["Rdata"])){
         
         $RM = new Reactionmodule;

         $uname = $_GET["uname"];
         $react = $_GET["react"];
         $cuser = $_SESSION["userid"];
         $Rdata = $_GET["Rdata"];
         $msgid = $_GET["msgid"];
         $sibReacted = $_GET["sibReacted"];
         $self = $_GET["self"];
        

         
         if($sibReacted=="false"){
              $reactArr = $RM->get_reaction($msgid);
              $reactArr["$Rdata"].=$cuser.";";
         $RM->updateRctn($Rdata,$reactArr["$Rdata"],$msgid);
         echo json_encode(["data"=>"$msgid$Rdata","cmd"=>"increase"]);
             
         }
         else{
$reactArr = $RM->get_reaction($msgid);
             #unreact
              if($self == "true"){
                   $rctlist  =  explode(";",$reactArr[$Rdata]);
                   $index =  array_search($cuser, $rctlist);
                  
                   array_splice($rctlist,$index,1);
                  $stringedlist  = implode(";",$rctlist);
                  $RM->updateRctn($Rdata,$stringedlist ,$msgid);
         echo json_encode(["data"=>"$msgid$Rdata","cmd"=>"decrease"]);

              }
             
        else{
       
         if(!isset($reactArr[$sibReacted])) $reactArr[$sibReacted] = "";
         $rctlist  =  explode(";",$reactArr[$sibReacted]);
         $index =  array_search($cuser, $rctlist);
       
         array_splice($rctlist,$index,1);
        $stringedlist  = implode(";",$rctlist);
              $RM->updateRctn($sibReacted,$stringedlist ,$msgid);
              

              $reactArr["$Rdata"].=$cuser.";";
         $RM->updateRctn($Rdata,$reactArr["$Rdata"],$msgid);
         echo json_encode(["data"=>["$msgid$Rdata","$msgid$sibReacted"],"cmd"=>["increase","decrease"]]);


             
         }
    }
    }
}


