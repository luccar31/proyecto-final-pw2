<?php

class ClientModel {
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function createClient($nickname, $firstname, $surname, $email){
        $this->database->query("INSERT INTO client VALUES ('$nickname', '$firstname', '$surname', '$email', NULL, NULL)");
    }
}