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
        $id = $_GET['id'];
        $data['id'] = $id;

        $this->printer->generateView('ticketView.html', $data);
    }

    public function createTicket(){
        $id_flight = $_GET['id'];
        $id_type_cabin = $_POST['type_cabin'];
        $id_service = $_POST['service'];
        $num_tickets = $_POST['num_tickets'];
        $userNickname = $_SESSION['nickname'];


        $data = $this ->ticketModel->validateCapacityCabin($id_flight, $id_type_cabin, $num_tickets);
        $data3 = $this ->ticketModel-> calculatePrice($id_flight);

        if($data['isValid'] == true){
            $this->ticketModel->createTicket($id_flight, $id_type_cabin, $id_service, $userNickname, $num_tickets);
            $ticketsClient = $this->findClientTickets();
            $this->printer->generateView('reserved_ticketsView.html', $ticketsClient);
        }else{
            $this->printer->generateView('ticketView.html', $data);
        }





    }

    public function showClientTickets(){
        $ticketsClient = $this->findClientTickets();
        $this->printer->generateView('client_ticketsView.html', $ticketsClient);
    }

    private function findClientTickets(){
        return $this->ticketModel->findClientTickets($_SESSION["nickname"]);
    }





}