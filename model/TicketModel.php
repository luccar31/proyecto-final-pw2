<?php

class TicketModel{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function search($typeCabin = null, $typeService = null){

        if($typeCabin){
            $result =  $this->database->query("");
            return  ['flights' => $result];
        }

    }
}