<?php

class Helper
{

    public static function redirect($url)
    {
        header("location: $url");
        exit();
    }

    public static function debugExit($var)
    {
        var_dump($var);
        echo "<br>";
        exit();
    }

    public static function debug($var)
    {
        var_dump($var);
        echo "<br>";
    }
}