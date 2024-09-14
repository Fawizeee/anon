   
<?php

  // INCLUDE SQLITE CONNECTION
  require("../module/connection.php");
  //INCLUDE COOKIE MAKER AND INITIALIZER
  require("../module/cookie.php");
  $cookie_mod = new cookie_mod();
  require("../module/idcookiemaker.php");
  require("../module/autologin.php");
require("../module/password.php");
include "../module/handlebarsTemplate.php";

$handlebars = new HandlebarTemplate(templateString: file_get_contents(filename: "views/login.hbs"));
  

  $db = new DbConn();
  $db = $db->db;
  $password_mod = new Password_mod();
  try{
  
        // autologin if possible
        if(isset($_COOKIE))
        {      
            if(isset($_COOKIE["remember"]))
            { 
                $autologin = new Login($db);
                $_SESSION["autologin"] = true;
                // $cookie_mod->Make_ckie(["autologin"=>"yes"]);
                 [$canlogin,$message,$row] =  $autologin->autologin($_COOKIE);
                 if($canlogin){ header(header: "location:/anon/messages?name=$row[username]"); exit;}
                 
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
      session_start();
        // IF THERES A POST REQUEST CONTAINING NAME AND PASSWORD
     
        if(isset($_POST["name"]) && isset($_POST["pword"]) ){
        

       $post = true;
            
      
   
  
        $name = strtolower(string: trim(string: $_POST["name"]));
        $pword= strtolower(string: trim(string: $_POST["pword"]));
        $rem = (bool)isset($_POST["remember"]);

        //CHECK IF THE PERSON HAS SIGNED UP
        //DATABASE CODE FOR QUERY
        $login = new login($db);

     [ $canlogin,$message,$row]= $login->login(["name"=>$name,"password"=>$pword],$password_mod)["bool"];
      // $msg =  $login->login()["msg"];
      // $row = isset($login->login()["row"]) ?$login->login()["row"]:false;
            //IF PASSWORD IS CORRECT LOGIN
          
          if($canlogin){
                $isUser = true;
              
                $_SESSION["loggedin"] = true;
                $_SESSION["name"] = $name;  
          
            
               $cookie_mod->Make_ckie(cookie: ["remember"=>uniqid()]);
           

              //IF THERES NO ID CREATE AND INSERT IDFOR THE USER
           if (!$row["id"]){

    $user_ckie = $newcookie->makeidcookie();
     $cookie_mod->Make_ckie(["userid"=> $user_ckie]);
    $_SESSION["userid"] = $user_ckie;
    $stmt = $db->prepare( query: "UPDATE INFO SET ID =? ,REMEMBER=? WHERE USERNAME =?");
    $stmt->bind_Param("sss",$user_ckie,$rem_id,$name);
  $result  = $stmt->execute();
    
    if ($result){

       $saved = true;
       $class = "success";

    }

            }
            //IF THERES AN ID ALREADY PUT IN IT IN THE COOKIE
            else if($row["id"])
            { 
                //  $cookie_mod->make_ckie(["userid"=> $row["id"]]); 
                 $_SESSION["userid"] =  $row["id"];
                 $class = "success";
                  
                 $user_ckie=$row["username"];var_dump($user_ckie);
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
            
            if ($class=="success"){ header("location:/anon/messages?name=$name");  exit; }
          
         $data = ["message"=>$message ,"class"=>$class,"isUser"=>$isUser ,"post"=>!$post];

session_write_close(); ;var_dump($_SESSION,$rem);
echo $handlebars->render($data);





$db->close();
        }
    ?>
  
