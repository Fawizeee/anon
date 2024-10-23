<?php

namespace Anon\Database;


class Connection{
    public $conn;
    public $db;

      public function __construct() {
     
        $this->db = $this->conn();
        $this->createTable();





}

    public function conn(){
    $this->conn =   new \mysqli("localhost","root","","anonymous");

        return $this->conn;
    }
    private function createTable() {
        try {
            $message  = $this->db->prepare(
                " CREATE TABLE IF NOT EXISTS MESSAGES
                    (
                    USERID TEXT NOT NULL,
                    SENDERID TEXT NULL,
                    MSGID TEXT NULL,
                    MSG TEXT NULL,
                    FUNNY TEXT NULL,
                    SAD TEXT NULL,
                    BORING TEXT NULL,
                    CRAZY TEXT NULL,
                    CTIME TEXT NOT NULL,
                    USERNAME VARCHAR(255),
                    FOREIGN KEY (USERNAME) REFERENCES INFO (USERNAME)
                    ) "
            );

            $Ctable  = $this->db->prepare(
                "CREATE TABLE  IF NOT EXISTS INFO
                (
                    ID TEXT NULL,
                    USERNAME VARCHAR(255) PRIMARY KEY,
                    PASSWORD TEXT NOT NULL
                )"
            );

            $Ctable->execute();
            $ret = $Ctable->get_result();
           $message->execute();
           $retm = $message->get_result();

            if ($ret && $retm) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}



