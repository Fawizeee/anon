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
        
        if($password_mod!==null){
        if (empty($credentials["name"]) || empty($credentials["password"])) {
            return ["isUser" => false, "msg" => "Please fill in all fields","row"=>[]];
        }
}
        try{
        if ($result->num_rows === 0) {
            return ["isUser" => false, "msg" => "User does not exist. Sign up!","row"=>[]];
        }

           $row = $result->fetch_assoc();

           var_dump($credentials);
           if($password_mod!=null){
         $verified_password = $password_mod->verifyPassword($row["password"],$credentials["password"]);
        if ($verified_password) {
            $_SESSION["loggedin"] = true;
            return ["isUser" => true, "msg" => "", "row" => $row];
        } else {
            return ["isUser" => false, "msg" => "Wrong password","row"=>[]];
        }
}     elseif($row["id"]==$_COOKIE["userid"]){
    return ["isUser" => true, "msg" => "", "row" => $row];
        }
    } catch (mysqli_sql_exception $e) {
        return ["isUser" => false, "msg" => "Database error: " . $e->getMessage(),"row"=>[]];
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
       $result = $this->query($this->db,$credentials["remember"],$this->Coloumn["remember"]);
       $authenticated = $this->authenticate($result,$credentials,null);
       return $authenticated;
       
    }

   
   
}