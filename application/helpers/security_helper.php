<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('addHoneyPot')){
    function addHoneyPot(){
        echo '<p style="display:none;"><input name="body" type="text" size="30" maxlength="30" class="verif-hp"/></p>';
        echo '<p class="message"><input name="message" type="text" size="30" class="verif-hp"/></p>';
    }
}

if (!function_exists('isBot')){
    function isBot($fields){
        if(strlen($fields['body']) || strlen($fields['message'])) return true;
        return false;
    }
}

if (!function_exists('outputData')){
    function outputData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

if (!function_exists('secure_url_param')){
    function secure_url_param($data, $numeric) {
        if ($numeric){
             $correct = is_numeric($data);
             if ( $correct ) { return $data; }
             elseif
                ( !$correct ) { return false; }
        }

        if ($data){
             $correct = preg_match('/^[a-z0-9_]*$/i', $data);

             if ( $correct ) { return $data; }
             elseif
                ( !$correct ) { return false;}
        }

        return false;
    }
}

function get_profil_image($id){

}