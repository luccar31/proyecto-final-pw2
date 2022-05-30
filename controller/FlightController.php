<?php

class FlightController
{
    private $printer;
    private $flightModel;

    public function __construct($appointmentModel, $printer) {
        $this->flightModel = $appointmentModel;
        $this->printer = $printer;
    }

    public function execute(){
        $this->printer->generateView('flightView.html');
    }

    public function getFlights(){
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $flights = $this->flightModel->getFlights($origin, $destination);
        $data = ["flights" => $flights];

        if (empty($flights)){
            $this->printer->generateView('flightView.html', ['error' => 'No hay vuelos disponibles']);
        }
        else{
            $this->printer->generateView('flightView.html', $data);
        }

    }
}