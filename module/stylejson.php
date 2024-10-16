<?php
class load_style_json{
    public function style(){
        $json = file_get_contents("../public/json/styles.json");
        $json = json_decode($json, true);
        return $json;
    }
}

