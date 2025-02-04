<?php
    namespace Anon\Src;
class updateUserLoginInfo{
    private $remember;
    public $id;
    private $rem;
    private $name;
    private $userid;
    private $db;
    private $addId;
    private $setCookie;
    private $db_id;

    public function __construct(\mysqli $db,bool $rem,$db_id,$userid,$name){
        $this->db = $db;
        $this->rem = $rem;
        $this->db_id = $db_id;
        $this->userid = $userid;
        $this->addId=false;
       
        $this->name = $name;
    }
    private function remember (){
      
            if(!$this->rem){
                $this->remember = 0;
            }
            else{                
                $this->remember = uniqid(mt_rand(10,100));
               $this->setCookie->Make_ckie(cookie: ["remember"=>$this->remember]);
            }


    }
    private function id(){

        $this->remember();
        if(is_null($this->db_id)){  
            $this->id = $this->userid;
            $this->addId=1;
     return  "UPDATE INFO SET ID =?,remember =? WHERE USERNAME =?;";
        }
        else{
            $this->id=$this->db_id;
 
       return "UPDATE INFO SET remember=? WHERE USERNAME =?";
;
   } }

    public function update(){
        $query = $this->id();
        $stmt = $this->db->prepare( $query);
        if ($this->addId){
    $stmt->bind_Param("sss",$this->id,$this->remember,$this->name);

        }
        else{
    $stmt->bind_Param("ss",$this->remember,$this->name);
}
   $result = $stmt->execute();
   return [$result,$query,$this->remember,$this->id,$this->addId];


    }

}