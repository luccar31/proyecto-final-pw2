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
        $this->printer->generateView('ticketView.html');
    }


}