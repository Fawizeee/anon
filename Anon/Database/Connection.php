<?php

namespace Anon\Database;


class Connection{
    public $conn;
    public $db;

      public function __construct() {
     
        $this->db = $this->conn();
 





}

    public function conn(){
    try{  
          $this->conn =   new \mysqli("localhost","root","","anonymous");
}
catch(\Exception $e){
    echo "error connecting to database";exit();
}
        if($this->conn)
        return $this->conn;
    }
  

}



