<?php


require_once '../bootstrap.php';

use Anon\Src\{cookie_mod,HandlebarTemplate,idcookie};
// use Cradle\Handlebars\HandlebarsHandler as Handlebars;
// require dirname(__DIR__) . '\vendor\autoload.php';
$templateString = file_get_contents("../public/views/message.hbs");
$handlebars = new HandlebarTemplate($templateString);




$db = $db->db;
$cookie_mod = new cookie_mod();
$row="";

try {
    if (isset($_SERVER["QUERY_STRING"])) {
        $person =strtolower($_GET["name"]);

        // Check if user is available
        $stmt = $db->prepare("SELECT  USERNAME,ID FROM INFO WHERE USERNAME = ?");
        $stmt->bind_Param("s", $person);
         $ret =   $stmt->execute();
        $result = $stmt->get_result();
        $userAvail =$row= $result->fetch_array();
        if (isset($userAvail["ID"])) {

            
            $id = new idcookie(); 
            $id_string = $_SESSION["userid"]??$_COOKIE['id']?? $id->makeidcookie();
            $_SESSION["userid"]= $id_string;
            $cookie_mod->Make_ckie( ["id"=>$id_string]) ;
            $id_msg = $id->makeidcookie();
            session_write_close();
            
            if (isset($_POST["message"])) {
                $message = $_POST["message"];
                if (empty($message)) {

                    throw new Exception(message: "No message to send");

                }

                $date = date('D:M:Y');
                $sql = "INSERT INTO MESSAGES(USERID, SENDERID, MSG, MSGID, USERNAME,CTIME)
                        VALUES(?,?,?,?,?,?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_Param("ssssss", $userAvail["ID"],$id_string,$message,$id_msg,$row["USERNAME"],$date);
        
               $ret =  $stmt->execute();
           

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
$handlebars->registerPartials("nav",file_get_contents(filename:"../public/views/nav.hbs"));

    echo $handlebars->render($data);
}
echo $_COOKIE['id']; 
echo $id_string;
