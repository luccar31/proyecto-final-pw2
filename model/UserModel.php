<?php

class UserModel {
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function createUser($nickname, $password){
        $this->database->query("INSERT INTO user (nickname, password, role)
                                VALUES ('$nickname', '$password', 1)");
    }

    public function getUser($nickname, $password){
        return $this->database->query("SELECT nickname FROM user
                                       WHERE nickname='$nickname'
                                       AND password='$password'");
    }

    public function getUserNickname($nickname){
        return $this->database->query("SELECT nickname FROM user
                                       WHERE nickname='$nickname'");
    }

    public function getUserEmail($email){
        return $this->database->query("SELECT email FROM client
                                       WHERE email='$email'");
    }


}