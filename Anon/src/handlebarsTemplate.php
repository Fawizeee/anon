<?php

namespace Anon\Src;
use Cradle\Handlebars\HandlebarsHandler as Handlebars;
class HandlebarTemplate {
    public $templateString;
    public $handlebars;
    public $text;
   function __construct( String $templateString) {
    $this->handlebars =new Handlebars();
    $this->templateString = $templateString;
}

    public function compile(){
   return $this->handlebars->compile($this->templateString);
    }
    public function render(array $data ){
      $template =  $this->compile();
      return $template($data);

    }
  function  registerPartials($name,$template){
    $this->handlebars->registerPartial($name,$template); 
  }
  function registerHelpers(string $helper){
     
   $this->handlebars->registerHelper($helper,function($text){
        return nl2br(htmlspecialchars($text));
    });
  }
}