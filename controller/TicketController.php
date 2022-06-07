<?php

class TicketController{

    private $printer;
    private $flightModel;
    private $userModel;
    private $ticketModel;

    public function __construct($models, $printer){
        $this->flightModel = $models["flightModel"];
        $this->ticketModel = $models["ticketModel"];
        $this->printer = $printer;
    }

    public function execute(){
        $id = $_GET["id"];
        $data["id"] = $id;
        $this->printer->generateView('ticketView.html', $data);
    }

    public function createTicket(){
        $flight_id = $_GET["id"];
        $type_cabin = $_POST["type_cabin"];
        $service = $_POST["service"];

        $ticket = $this->ticketModel->createTicket($flight_id, $type_cabin, $service);
        $this->printer->generateView('ticketView.html', $ticket);

    }

    public function findClientTickets(){
        $clientTickets = $this->ticketModel->findClientTickets($_SESSION["nickname"]);
        $this->printer->generateView('tickets.html', $clientTickets);
    }


}