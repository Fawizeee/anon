<?php
    require 'bootstrap.php';

    $db= $db->db;
        try {
            $message  =$db->prepare(
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

            $Ctable  =$db->prepare(
                "CREATE TABLE  IF NOT EXISTS INFO
                (
                    ID TEXT NULL,
                    USERNAME VARCHAR(255) PRIMARY KEY,
                    REMEMBER VARCHAR(100) NULL,
                    PASSWORD TEXT NOT NULL
                )"
            );
            $SelectIDTable = $db->prepare(
                "CREATE TABLE IF NOT EXISTS SELECTTABLE(
                    ID TEXT NOT NULL,
                    SELECTID VARCHAR(100) NOT NULL
                )"
            );

          $ret =  $Ctable->execute();
          $retm = $message->execute();
           $sitExec = $SelectIDTable->execute();


            if ($ret && $retm && $sitExec) {
                echo "done";
            } else {
                echo $SelectIDTable->execute();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
