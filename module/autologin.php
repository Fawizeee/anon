<?php

class Login
{
    private $db; //mysqli
    private $name; //lobin username
    private $password;
    private $password_mod; // module to check password
    private $Coloumn = [
        "username" => "username",
        "remember" => "remember"
    ]; //array that holds coloumn name for interchanging

    /**
     * Constructor
     *
     * @param string $name
     * @param string $password
     * @param mysqli $db
     * @param Password_mod $password_mod
     * @param array $coloumn
     */
    public function __construct(mysqli $db)
    {

        $this->db = $db;
    }

    /**
     * Summary of query
     * @param mysqli $db
     * @param string $name
     * @param string $Coloumn
     * @return bool|mysqli_result
     */
    public function query(mysqli $db, string $name, string $Coloumn): bool|mysqli_result
    {
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
    /**
     * Summary of authenticate
     * @param mysqli_result $result
     * @param array $credentials
     * @param Password_mod|null $password_mod
     * @return array
     */
    public function authenticate(mysqli_result $result, array $credentials, Password_mod|null $password_mod)
    {
        // verifies credentials are not empty
        if ($password_mod !== null) {
            if (empty($credentials["name"]) || empty($credentials["password"])) {
                return ["isUser" => false, "msg" => "Please fill in all fields", "row" => []];
            }
        }
        //check if user exists
        try {
            if ($result->num_rows === 0) {
                return ["isUser" => false, "msg" => "User does not exist. Sign up!", "row" => []];
            }

            $row = $result->fetch_assoc();
            if ($password_mod != null) {
                $verified_password = $password_mod->verifyPassword($row["password"], $credentials["password"]);
                //check if password is correct

                if ($verified_password) {

                    // if it is correct input neccesary sessions and cookies
                    $_SESSION["loggedin"] = true;
                    // return user authenticated;
                    return ["isUser" => true, "msg" => "", "row" => $row];
                } else {
                    // else wrong paaswoord
                    return ["isUser" => false, "msg" => "Wrong password", "row" => []];
                }
                //if user data id  matches cookie id authenticate(for autologin)
            } elseif ($row["id"] == $_COOKIE["userid"]) {
                return ["isUser" => true, "msg" => "", "row" => $row];
            }
        } catch (mysqli_sql_exception $e) {
            //display error
            return ["isUser" => false, "msg" => "Database error: " . $e->getMessage(), "row" => []];
        }
        return [];
    }
    /**
     * Summary of login
     * @param array $credentials
     * @param Password_mod $password_mod
     * @return array
     */
    public function login(array $credentials, Password_mod $password_mod): array
    {



        $result = $this->query($this->db, $credentials["name"], $this->Coloumn["username"]);
        $authenticated = $this->authenticate($result, $credentials, $password_mod);
        return $authenticated;


    }
    /**
     * Summary of autologin
     * @param array $credentials
     * @return array
     */
    public function autologin(array $credentials)
    {
        $result = $this->query($this->db, $credentials["remember"], $this->Coloumn["remember"]);
        $authenticated = $this->authenticate($result, $credentials, null);
        return $authenticated;

    }



}