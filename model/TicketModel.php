<?php

class TicketModel{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function search($typeCabin = null, $typeService = null){

        if($typeCabin){

        }

    }

    public function createTicket($flight_id, $type_cabin, $service){
      $this->database->query("INSERT INTO ticket (id_cabin, id_flight, id_service) VALUES ('$type_cabin', '$flight_id', '$service')");
      $ticket_id = $this->database->query("SELECT LAST_INSERT_ID() AS id");
      $client_nickname = $_SESSION["nickname"];
      $ticket_id = ($ticket_id[0]["id"]);
      $this->database->query("INSERT INTO client_ticket (user_nickname, id_ticket) VALUES ('$client_nickname', '$ticket_id')");

    }

    public function findClientTickets($nickname){
        $resu = $this->database->query("SELECT * FROM client_ticket WHERE user_nickname = '$nickname'");
        return ['tickets' => $resu];
    }
}