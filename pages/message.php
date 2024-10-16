<?php
session_start();
session_write_close();
require_once "../module/connection.php";
require_once "../module/idcookiemaker.php";
require_once "../module/cookie.php";
include "../module/handlebarsTemplate.php";

// use Cradle\Handlebars\HandlebarsHandler as Handlebars;
// require dirname(__DIR__) . '\vendor\autoload.php';
$templateString = file_get_contents("../public/views/message.hbs");
$handlebars = new HandlebarTemplate($templateString);



$db = new DbConn();
$db = $db->db;
$cookie_mod = new cookie_mod();
$row = "";

try {
    if (isset($_SERVER["QUERY_STRING"])) {
        $query = explode("/", $_SERVER["QUERY_STRING"]);
        $q_id = explode("=", $query[0]);
        $person = strtolower($q_id[1]);

        // Check if user is available
        $stmt = $db->prepare("SELECT  USERNAME FROM INFO WHERE USERNAME = ?");
        $stmt->bind_Param("s", $person);
        $ret = $stmt->execute();
        $result = $stmt->get_result();
        $userAvail = $row = $result->fetch_array();

        if ($userAvail) {

            if (isset($_POST["message"])) {

                $id = new idcookie();
                $id_string = $_SESSION["userid"] ?? $id->makeidcookie();
                $id_msg = $id->makeidcookie();
                $message = $_POST["message"];
                var_dump($message);
                if (empty($message)) {

                    throw new Exception("No message to send");

                }

                $date = date('D:M:Y');
                $sql = "INSERT INTO MESSAGES(USERID, SENDERID, MSG, MSGID, USERNAME,CTIME)
                        VALUES(?,?,?,?,?,?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_Param("ssssss", $person, $id_string, $message, $id_msg, $row["USERNAME"], $date);

                $ret = $stmt->execute();


                if (!$ret) {

                    throw new Exception("Message not sent");
                }
            }
        } else {

            throw new Exception("User not found");
        }
    }
} catch (Exception $e) {
    $msg = $e->getMessage();
} finally {
    $data = [
        "message" => $msg ?? null,
        "name" => $person ?? null,
        "action" => $_SERVER["REQUEST_URI"] ?? null,
    ];

    echo $handlebars->render($data);
}