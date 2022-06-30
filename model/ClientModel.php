<?php

class ClientModel {
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function createClient($nickname, $firstname, $surname, $email){
        $this->database->query("INSERT INTO client VALUES ('$nickname', '$firstname', '$surname', '$email', NULL, NULL, false)");
    }

    public function getClient($nickname){
        return $this->database->query("SELECT * FROM client WHERE user_nickname = '$nickname'");
    }

    public function getVerificationState($nickname){
        return (bool)$this->database->query("SELECT verification FROM client WHERE user_nickname = '$nickname'")[0]['verification'];
    }

    public function updateVerificationState($nickname){
        $this->database->query("UPDATE client SET verification = true WHERE user_nickname = '$nickname'");
    }

    public function getEmail($nickname){
        return $this->database->query("SELECT email FROM client WHERE user_nickname = '$nickname'")[0]['email'];
    }
}