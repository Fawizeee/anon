<?php

class Login {
    private $db;
    private $name;
    private $password;
    private $password_mod;
    private $Coloumn = [
        "username"=>"username",
        "remember"=>"remember"
    ];

    /**
     * Constructor
     *
     * @param string $name
     * @param string $password
     * @param mysqli $db
     */
    public function __construct( mysqli $db ) {
      
        $this->db = $db;
    }
    public function query(mysqli $db,string $name,string $Coloumn): bool|mysqli_result{
        $query = "
        SELECT
            password,username,id
        FROM
            info
        WHERE
            $Coloumn = ?
    ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    return $stmt->get_result();


        
    }

    public function authenticate(mysqli_result $result,array $credentials,Password_mod|null $password_mod){
        
        
        if (empty($credentials["name"]) || empty($credentials["password"])) {
            return ["bool" => false, "msg" => "Please fill in all fields"];
        }

        try{
        if ($result->num_rows === 0) {
            return ["bool" => false, "msg" => "User does not exist. Sign up!"];
        }

           $row = $result->fetch_assoc();
           if($password_mod!=null){
         $verified_password = $this->password_mod->verifyPassword($row["password"],$credentials["password"]);
        if ($verified_password) {
            $_SESSION["loggedin"] = true;
            return ["bool" => true, "msg" => "", "row" => $row];
        } else {
            return ["bool" => false, "msg" => "Wrong password",$row=>[]];
        }
   
}     elseif($row["id"]==$_COOKIE["id"]){
    return ["bool" => true, "msg" => "", "row" => $row];
        }
    } catch (mysqli_sql_exception $e) {
        return ["bool" => false, "msg" => "Database error: " . $e->getMessage()];
    } finally {
       
    }
    }
    /**
     * Login
     *
     * @return array
     */
    public function login(Array $credentials,Password_mod $password_mod): array{



       $result = $this->query($this->db,$credentials["name"],$this->Coloumn["username"]);
       $authenticated = $this->authenticate($result,$credentials,$password_mod);
       return $authenticated;

         
    }
    public function autologin(array $credentials){
       $result = $this->query($this->db,$credentials["key"],$this->Coloumn["remember"]);
       $authenticated = $this->authenticate($result,$credentials["name"],null);
       return $authenticated;
       
    }

   
   
}