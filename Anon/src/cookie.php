<?php
    namespace Anon\Src;

class cookie_mod {

   public function Make_ckie(array $cookie){
      foreach ($cookie as $key => $value) {
         setcookie($key, $value, time() + (86400 * 30), "/");
     }
    
    }
    public function chk_cookie($location){
       if(!isset($_COOKIE)) {header("location:$location");exit;}
       else{
        return 1;
       }
    }

}