<?php

class TicketController{

    private $printer;
    private $flightModel;
    private $userModel;
    private $ticketModel;

    public function __construct($models, $printer){
        $this->userModel = $models["userModel"];
        $this->flightModel = $models["flightModel"];
        $this->ticketModel = $models["ticketModel"];
        $this->printer = $printer;
    }

    public function execute(){
        $flights = $this->flightModel->getAllFlights();
        $this->printer->generateView('flightView.html', $flights);
    }


}