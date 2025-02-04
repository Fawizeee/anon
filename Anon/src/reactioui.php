<?php  


namespace Anon\Src;



/**
     * Class getReactionUIData: Handles reactions for a message
     */
    class getReactionUIData{
        /**
         * Public properties
         */
        public $name;
        public $uname;
        public $msgid;
        public $rctArr;
        public $rctClr = [];
       public $styles;
       private $fontAwesome;

        /**
         * Constructor: Initializes the object with provided parameters
         * @param $name
         * @param $uname
         * @param $msgid
         * @param $rctArr
         */
        public function __construct(String $name, String $uname, String $msgid,Array $rctArr){
            $this->name =$name;
            $this->uname = $uname;
            $this->msgid = $msgid;
            $this->rctArr = $rctArr ;
         
        } 
          /**
             * Initialize reaction colors
             */
        private function loadStyleJson(){
            $style_json = new load_style_json;
            $this->styles=  $style_json->style();
            $this->rctClr =$this->styles["reactclr"];
            $this->fontAwesome = $this->styles["fontawesome"];
            return    $this->fontAwesome;
        }
        
        /**
         * Private function: Counts reactions for each type
         * @return array
         */
        private function countReactions(){
            $reactionCounts = [];
            $hasReacted = [];
            foreach ($this->rctArr as $key => $value) {
                if(empty($value)){
                    $reactionCounts[$key] = 0;
                    $hasReacted[$key] = false; // Initialize $hasReacted[$key] to false
                }else{
                    $rctList = explode(";",$value);
                    $hasReacted[$key] = in_array($this->uname,$rctList);
                    $reactionCounts[$key] = count($rctList)-1;
                
                }  
            } 
            return [$reactionCounts, $hasReacted];
        }
       
        /**
         * Public function: Generates the reaction UI
         */
       public function getReactionUIData(){
            // if(empty($this->rctArr) || empty($this->rctClr)){
            //     // Handle the case where $this->rctArr or $this->rctClr is empty
            //     return;
            // }
           $this->fontAwesome  = $this->loadStyleJson();
     [$reactionCounts,$hasReacted] =  $this->countReactions();
            $reactionUiData = [];
             
            foreach($this->rctClr as $key => $value){  
                $length = !empty($reactionCounts[$key])? $reactionCounts[$key]:0 ;
                $keyVal = $hasReacted[$key]?$this->rctClr[$key]:"white";

                $reactionUiData[] = [
                    "msgid" => $this->msgid,
                    "name" => $this->name,
                    "uname" => $this->uname,
                    "key" => $key,
                    "reacted" => $hasReacted[$key]?true:false,
                    "keyclr" => $keyVal,
                    "length" => $length,
                    "fa" => $this->fontAwesome[$key],
                ];
   
            }
            return $reactionUiData;
       }
    }