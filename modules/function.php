<?php
class Helper{
    public function isJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }
}