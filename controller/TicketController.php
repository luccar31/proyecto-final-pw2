<?php

class TicketController{

    private $printer;
    private $flight_planModel;
    private $ticketModel;
    private $appointmentModel;

    public function __construct($models, $printer){
        $this->flight_planModel = $models['flight_planModel'];
        $this->ticketModel = $models['ticketModel'];
        $this->appointmentModel = $models['appointmentModel'];
        $this->printer = $printer;
    }

    public function execute(){

        $this->printer->generateView('ticketView.html');
    }

    //info de los precios del vuelo elegido
    public function payInfo(){

        $data['id_type_cabin'] = $_POST['type_cabin'];
        $data['id_service']  = $_POST['service'];
        $data['num_tickets']  =  $_POST['num_tickets'];

        $_SESSION['id_type_cabin'] = $_POST['type_cabin'];
        $_SESSION['id_service']  = $_POST['service'];
        $_SESSION['num_tickets']  =  $_POST['num_tickets'];

        $data['price'] = $this->ticketModel->calculatePrice($_SESSION['id_flight_plan'], $data['num_tickets'], $data['id_service'], $data['id_type_cabin']);

        $this->printer->generateView('priceView.html', $data);


    }

    //crea ticket y el vuelo en caso de que no exista
    public function createTicket(){

       /* $data = $this ->ticketModel->validateCapacityCabin($_SESSION['id_flight_plan'], $id_type_cabin, $num_tickets);*/


        /*if($data['isValid'] == true){*/


        //si inició sesión:
        if (isset($_SESSION['nickname'])) {

            $data['enabledClient'] = $this->appointmentModel->getAppointment($_SESSION['nickname']);

            //inició sesión pero no realizó chequeo médico
            if (empty($data['enabledClient'])) {

                $data['disabledClient'] = "Debe realizar un chequeo médico. El código de viajero y nivel de vuelo es requerido.";

            } //inició sesión y realizó chequeo médico. Hace la reserva.
            else {
                $data['id_flight'] = $this->flight_planModel->createFlight($_SESSION['id_flight_plan'], $_SESSION['departure_date'], $_SESSION['departure_time'], $_SESSION['departure'], $_SESSION['week']);
                $this->ticketModel->createTicket($data['id_flight'], $_SESSION['id_type_cabin'], $_SESSION['id_service'], $_SESSION['nickname'], $_SESSION['num_tickets'], $_SESSION['departure'], $_SESSION['destination']);
                $data['ticket'] = $this->ticketModel->findClientTicket($data['id_flight'], $_SESSION['nickname']);
                $data['enabledClient'] = "Su vuelo ha sido reservado! N° de vuelo: ";
            }

        }
        //no inició sesión
        else {
            $data['notLogged'] = "Debe inciar sesión para reservar vuelos";
        }

            $this->printer->generateView('reserved_ticketsView.html', $data);

      /*  }else{
            $this->printer->generateView('ticketView.html', $data);
        }*/

    }

    //selecciona cabina y servicio
    public function selectCabinAndService(){

        $data['cabins'] = $this->ticketModel->getCabins($_GET["id"]);
        $data['services'] = $this->ticketModel->getServices($_GET["id"]);

        //guardo los datos del vuelo elegido en sesión para utilizarlos mas tarde
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
        $ticketsClient['tickets'] = $this->findClientTickets();
        $this->printer->generateView('client_ticketsView.html', $ticketsClient);
    }

    private function findClientTickets(){
        return $this->ticketModel->findClientTickets($_SESSION["nickname"]);
    }





}