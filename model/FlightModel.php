<?php

class FlightModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getFlights(){
        return $this->database->query("SELECT * FROM flight");
    }
}