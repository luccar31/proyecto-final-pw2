<?php

class TicketController{

    private $printer;
    private $printer2;
    private $pdf;
    private $flight_planModel;
    private $ticketModel;
    private $appointmentModel;

    public function __construct($models, $printer, $printer2, $pdf){
        $this->flight_planModel = $models['flight_planModel'];
        $this->ticketModel = $models['ticketModel'];
        $this->appointmentModel = $models['appointmentModel'];
        $this->printer = $printer;
        $this->printer2 = $printer2;
        $this->pdf = $pdf;
    }

    public function execute(){
        $this->printer->generateView('ticketView.html');
    }

    public function verifyEnabledClient()
    {
        //si inició sesión:
        if (isset($_SESSION['nickname'])) {

            $data['enabledClient'] = $this->appointmentModel->getAppointment($_SESSION['nickname']);


            //inició sesión pero no realizó chequeo médico
            if (empty($data['enabledClient'])) {

                $data['disabledClient'] = "Debe realizar un chequeo médico. El código de viajero y nivel de vuelo es requerido.";
                $this->printer->generateView('reserved_ticketsView.html', $data);

            } //inició sesión y realizó chequeo médico. Hace el pago
            else {
                Helper::redirect('/credit/pay');

            }
        }
        //no inició sesión
        else {
            $data['notLogged'] = "Debe inciar sesión para reservar vuelos";
            $this->printer->generateView('reserved_ticketsView.html', $data);
        }

        $this->printer->generateView('reserved_ticketsView.html', $data);
    }

    //crea ticket y el vuelo en caso de que no exista
    public function createTicket()
    {

        //si createTicketComplete es falso quiere decir que puede buscar otro vuelo
        if ($_SESSION['createTicketComplete'] == false) {
            $data['id_flight'] = $this->flight_planModel->createFlight($_SESSION['id_flight_plan'], $_SESSION['departure_date'], $_SESSION['departure_time'], $_SESSION['departure'], $_SESSION['week']);
            $_SESSION['id_flight'] = $data['id_flight'];
            $this->ticketModel->createTicket($data['id_flight'], $_SESSION['type_cabin'], $_SESSION['service'], $_SESSION['nickname'], $_SESSION['num_tickets'], $_SESSION['departure'], $_SESSION['destination']);

            $data['ticket'] = $this->ticketModel->findClientTicket($data['id_flight'], $_SESSION['nickname'], $_SESSION['type_cabin']);

            $data['enabledClient'] = "Su vuelo ha sido reservado! N° de vuelo: ";

            $_SESSION['createTicketComplete'] = true;
        } else {
            $data['error'] = "Ya reservó este vuelo";
        }

        $this->printer->generateView('reserved_ticketsView.html', $data);
    }

    public function generatePDF(){
        $data['ticket'] = $this->ticketModel->findClientTicket($_SESSION['id_flight'], $_SESSION['nickname'], $_SESSION['type_cabin']);
        $html = $this->printer2->generateTemplatedStringForPDF('templateTicketInfo.html', $data['ticket'][0]);
        $this->pdf->getPDF($html, 'ReservaVuelo');
    }

    //selecciona cabina y servicio
    public function selectCabinAndService(){
        //guardo los datos del vuelo elegido en sesión para utilizarlos mas tarde
        $_SESSION['id_flight_plan'] = $_POST["id"];
        $_SESSION['departure_date'] = $_POST["date"];
        $_SESSION['departure_time'] = $_POST["time"];
        $_SESSION['arrival_date'] = $_POST["date2"];
        $_SESSION['arrival_time'] = $_POST["time2"];
        $_SESSION['departure'] = $_POST["departure"];
        $_SESSION['destination'] = $_POST["destination"];
        $_SESSION['week'] = $_POST["week"];
        $_SESSION['hours'] = $_POST["hours"];


        $data['cabins'] = $this->ticketModel->getCabins($_SESSION['id_flight_plan']);
        $data['services'] = $this->ticketModel->getServices($_SESSION['id_flight_plan']);

        $this->printer->generateView('ticketView.html', $data);

    }

    //valida la cabina
    public function validateCabin(){

        $_SESSION['type_cabin'] = $_POST['type_cabin'];
        $_SESSION['service'] = $_POST['service'];
        $_SESSION['num_tickets'] = $_POST['num_tickets'];

        if ($_SESSION['num_tickets'] >= 1){
            $data = $this->ticketModel->validateCapacityCabin($_SESSION['id_flight_plan'], $_SESSION['type_cabin'], $_SESSION['num_tickets']);

            if ($data['isValid'] == true){

                Helper::redirect('/credit/payInfo');
            }
            else{
                $data['cabins'] = $this->ticketModel->getCabins($_SESSION['id_flight_plan']);
                $data['services'] = $this->ticketModel->getServices($_SESSION['id_flight_plan']);

                $this->printer->generateView('ticketView.html', $data);
            }
        }
        else{
            $data['cabins'] = $this->ticketModel->getCabins($_SESSION['id_flight_plan']);
            $data['services'] = $this->ticketModel->getServices($_SESSION['id_flight_plan']);
            $data['ceroTickets'] = "La cantidad mímina de tickets a elegir debe ser 1";

            $this->printer->generateView('ticketView.html', $data);
        }
    }

    public function showClientTickets(){
        $ticketsClient['tickets'] = $this->findClientTickets();
        $this->printer->generateView('client_ticketsView.html', $ticketsClient);
    }

    private function findClientTickets(){
        return $this->ticketModel->findClientTickets($_SESSION["nickname"]);
    }

}