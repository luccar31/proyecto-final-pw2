<?php

class FlightModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getFlights($origin, $destination){
        return $this->database->query("SELECT * FROM flight WHERE destination = '$destination' AND origin = '$origin'");
    }
}