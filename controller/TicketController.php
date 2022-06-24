<?php

class TicketController{

    private $printer;
    private $flightModel;
    private $ticketModel;

    public function __construct($models, $printer){
        $this->flightModel = $models['flightModel'];
        $this->ticketModel = $models['ticketModel'];
        $this->printer = $printer;
    }

    public function execute(){

        $this->printer->generateView('ticketView.html');
    }

    public function payInfo(){

        $data['id_type_cabin'] = $_POST['type_cabin'];
        $data['id_service']  = $_POST['service'];
        $data['num_tickets']  =  $_POST['num_tickets'];

        $data['price'] = $this->ticketModel->calculatePrice($_SESSION['id_flight_plan'], $data['num_tickets'], $data['id_service'], $data['id_type_cabin']);

        $this->printer->generateView('priceView.html', $data);


    }

    public function createTicket(){

/*
        $data = $this ->ticketModel->validateCapacityCabin($_SESSION['id_flight_plan'], $id_type_cabin, $num_tickets);


        if($data['isValid'] == true){


            $data['id_flight'] = $this->flight_planModel->createFlight($_SESSION['id_flight_plan'], $_SESSION['departure_date'], $_SESSION['departure_time'], $_SESSION['departure'], $_SESSION['week']);
            $this->ticketModel->createTicket($data['id_flight'], $id_type_cabin, $id_service, $userNickname, $num_tickets);
            $ticketsClient = $this->findClientTickets();
            $this->printer->generateView('reserved_ticketsView.html', $ticketsClient);
        }else{
            $this->printer->generateView('ticketView.html', $data);
        }*/

    }

    public function selectCabinAndService(){

        $data['cabins'] = $this->ticketModel->getCabins($_GET["id"]);
        $data['services'] = $this->ticketModel->getServices($_GET["id"]);

        $_SESSION['id_flight_plan'] = $_GET["id"];
        $_SESSION['departure_date'] = $_GET["date"];
        $_SESSION['departure_time'] = $_GET["time"];
        $_SESSION['arrival_date'] = $_GET["date2"];
        $_SESSION['arrival_time'] = $_GET["time2"];
        $_SESSION['departure'] = $_GET["departure"];
        $_SESSION['destination'] = $_GET["destination"];
        $_SESSION['week'] = $_GET["week"];
        $_SESSION['hours'] = $_GET["hours"];



                $this->printer->generateView('ticketView.html', $data);

    }

    public function showClientTickets(){
        $ticketsClient = $this->findClientTickets();
        $this->printer->generateView('client_ticketsView.html', $ticketsClient);
    }

    private function findClientTickets(){
        return $this->ticketModel->findClientTickets($_SESSION["nickname"]);
    }





}