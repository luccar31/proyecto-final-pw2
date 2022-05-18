<?php

class Session {

    public function __construct(){

    }

    public function isSessionActive(){
        return isset($_SESSION["logged"]);
    }
}