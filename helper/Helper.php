<?php

class Helper
{

    public static function redirect($url){
        header("location: $url");
        exit();
    }
}