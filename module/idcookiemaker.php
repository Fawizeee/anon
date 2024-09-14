<?php

class idcookie{
    public $number;
    public $letter;
    public $idcookieElem;
    public $idcookie;
    public function __construct(){
$this->number = [1,2,3,4,5,6,7,8,9,0];
$this->letter = ["a","b","d","e","g","h","i","k","l","o"];
 $this->idcookieElem =[$this->number,$this->letter];
    }
 function makeidcookie(){
    $this->idcookie="";
 
    for ($i=0; $i < 10; $i++) { 
     
    
       $pickedLet_num= $this->idcookieElem[mt_rand(0,1)];
       $pik_char =    $pickedLet_num[mt_rand(0,9)];
      $this->idcookie.= $pik_char;

    }
    return   $this->idcookie ;;
 }
}

//echo $newcookie->makeidcookie();