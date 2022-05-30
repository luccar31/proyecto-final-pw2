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
        $flights = $this->flightModel->getFlights();
        $data = ["flights" => $flights];
        $this->printer->generateView('flightView.html', $data);

    }
}