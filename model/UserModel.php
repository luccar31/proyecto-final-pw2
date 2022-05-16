<?php

class UserModel {
    private $database;

    public function __construct($database){
        $this->database = $database;
    }
    public function createUser($nickname, $password, $firstname){
        $this->database->query("INSERT INTO user (nickname, password, firstname)
                                VALUES ($nickname, $password, $firstname)");
    }

    public function getUser($nickname, $password){
        return $this->database->query("SELECT id FROM user
                                       WHERE nickname=$nickname 
                                       AND password=$password");
    }

    public function getUserNickname($nickname){
        return $this->database->query("SELECT id FROM user
                                       WHERE nickname=$nickname");
    }
}