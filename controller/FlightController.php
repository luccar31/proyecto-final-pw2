<?php

class FlightController
{
    private $printer;
    private $flightModel;

    public function __construct($appointmentModel, $printer)
    {
        $this->flightModel = $appointmentModel;
        $this->printer = $printer;
    }

    public function execute()
    {
        $this->printer->generateView('flightView.html');
    }

    public function getFlights()
    {
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $flightType = $_POST['flightType'];

        $data = $this->flightModel->getFlights($origin, $destination, $flightType);

        $this->printer->generateView('flightView.html', $data);

    }
}