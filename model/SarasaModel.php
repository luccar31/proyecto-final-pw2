<?php

class SarasaModel{

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    /*
    public function getSarasa(){
        return $this->database->query('SELECT * FROM sarasa');
    }
    */
}