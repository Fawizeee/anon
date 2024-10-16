<?php

// userregister.php

class UserRegister {
    private $db;
    private $passwordMod;

    public function __construct(DbConn $db, Password_mod $passwordMod) {
        $this->db = $db->db;
        $this->passwordMod = $passwordMod;
    }

    public function registerUser($name, $password) {
        $userAvail = true;
        $name = strtolower($name);
        $password = $this->passwordMod->hashPassword($password);

        $stmt = $this->db->prepare("SELECT * FROM INFO WHERE USERNAME = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->fetch_array()) {
            $userAvail = false;
            throw new Exception("Username taken");
        }

        if ($userAvail) {
            try {
                $stmt = $this->db->prepare("INSERT INTO INFO (USERNAME, PASSWORD) VALUES (?, ?)");
                $stmt->bind_param("ss", $name, $password);
                $ret = $stmt->execute();

                if ($ret) {
                    return true;
                } else {
                    throw new Exception("Error occurred while registering");
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }
}