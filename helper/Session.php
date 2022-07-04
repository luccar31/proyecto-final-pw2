<?php

class Session
{

    public function __construct()
    {

    }

    public static function isSessionActive()
    {
        return isset($_SESSION["logged"]);
    }

    public static function getNickname()
    {

        if (isset($_SESSION["user_firstname"])) {
            return $_SESSION["user_firstname"];
        }

    }
}