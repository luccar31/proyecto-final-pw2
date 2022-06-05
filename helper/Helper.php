<?php

class Helper
{

    public static function redirect($url){
        header("location: $url");
        exit();
    }

    public static function mostrarVarDump($data){
        echo var_dump($data);
        exit();
    }
}