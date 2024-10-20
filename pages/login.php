   
<?php
  session_start();
  // INCLUDE SQLITE CONNECTION
  require("../module/connection.php");
  //INCLUDE COOKIE MAKER AND INITIALIZER
  require("../module/cookie.php");
  $cookie_mod = new cookie_mod();
  require("../module/idcookiemaker.php");
  require("../module/autologin.php");
require("../module/password.php");
include "../module/handlebarsTemplate.php";
require "../module/updateUserloginInfo.php";

$loginPage = new HandlebarTemplate(templateString: file_get_contents(filename: "../public/views/login.hbs"));
$logoutPage = new HandlebarTemplate(templateString:file_get_contents(filename:"../public/views/logout.hbs"));
$logoutPage->registerPartials("nav",file_get_contents(filename:"../public/views/nav.hbs"));
$loginPage->registerPartials("nav",file_get_contents(filename:"../public/views/nav.hbs"));
  
  
  $db = new DbConn();
  $db = $db->db;
  $password_mod = new Password_mod();
  try{
      //  var_dump($_COOKIE);exit;
        // autologin if possible
        if(isset($_SESSION)&&isset($_SESSION["loggedin"])){
          echo $logoutPage->render(["id"=>$_SESSION["userid"]]);
          exit;
        }
        if(isset($_COOKIE)&&!isset($_SESSION["loggedin"]))
        {      
            if(isset($_COOKIE["remember"]))
            { 
              
                $autologin = new Login($db);
                  $login =  $autologin->autologin($_COOKIE);
                  $canlogin = $login["isUser"];
                  $message = $login["msg"];
                  $row = $login["row"];
                 
                 if($canlogin){ 
                  $cookie_mod->Make_ckie(["userid"=>$row["id"]]);
                  $isUser = true;
                  $name = $row["username"];
                  $_SESSION["loggedin"] = true;
                  $_SESSION["name"] = $name; 
                  $_SESSION["userid"] = $row["id"];
                  
                  header(header: "location:/anon/messages?id=$row[id]"); exit;}
                 
            }
            elseif(isset($_SESSION["checked"])&&$_SESSION["checked"]=="no")

            {
              $_SESSION["autologin"] = false;
                // $cookie_mod->Make_ckie(["autologin"=>"no"]);
                
            
                }
        }
    }
    
catch(Exception $e){$message = $e->getMessage();}

    $newcookie = new idcookie();
    // VALUE TO KNOW WHEN USERNAME IS AVAILABLE
  
       $isUser=  false;
       $post = false;
       $name;
       $pword;
       $message='';
       $class = "";
       $user_ckie;
       if(!isset($_SESSION["userid"])){
        $user_ckie = $newcookie->makeidcookie();
      //  $cookie_mod->Make_ckie(["userid"=> $user_ckie]);
      //  $_SESSION["userid"] = $user_ckie;

       }
      

    //   INITIALIZES DB CONNECTION
    
    try{
        // IF THERES A POST REQUEST CONTAINING NAME AND PASSWORD
     
        if(isset($_POST["name"]) && isset($_POST["pword"]) ){
        

       $post = true;
            
      
   
  
        $name = strtolower(string: trim(string: $_POST["name"]));
        $pword= strtolower(string: trim(string: $_POST["pword"]));
        $rem = (bool)isset($_POST["remember"]);

        //CHECK IF THE PERSON HAS SIGNED UP
        //DATABASE CODE FOR QUERY
        $login = new login($db);

     $login = $login->login(["name"=>$name,"password"=>$pword],$password_mod);
       $msg =  $login["msg"];
       $row = $login["row"];
       $canlogin = $login["isUser"];
      // $row = isset($login->login()["row"]) ?$login->login()["row"]:false;
            //IF PASSWORD IS CORRECT LOGIN
          if($canlogin){
                $isUser = true;
                $_SESSION["loggedin"] = true;
                $_SESSION["name"] = $name;  
        
          $updateInfo = new updateUserLoginInfo($db,$cookie_mod,$rem,$row["id"],$row["username"]);
           $updated = $updateInfo->update();
    if ($updateInfo){

       $saved = true;
       $class = "success";

    }

            
            //IF THERES AN ID ALREADY PUT IN IT IN THE COOKIE
            if($row["id"])
            { 
                //  $cookie_mod->make_ckie(["userid"=> $row["id"]]); 
                 $_SESSION["userid"] =  $row["id"];
                  $cookie_mod->Make_ckie(["userid"=> $row['id']]);

                 $class = "success";
                  
                 $user_ckie=$row["username"];var_dump($user_ckie,$canlogin);
                            }
                   
        }
           // RETURN WRONG PASSWORD 
      
  else  { throw new Exception($msg);}
 }  
        }
      
    
        catch(Exception $e){

           $message = $e->getMessage();
           $class = "error";
          
        }
        finally{ 

            if ($class=="success"){ header("location:/anon/messages?id=$row[id]");  exit; }
          
         $data = ["message"=>$message ,"class"=>$class,"isUser"=>$isUser ,"post"=>!$post];

session_write_close(); 
echo $loginPage->render($data);





$db->close();
        }
    ?>
  
