<?php

class FlightController
{
    private $printer;
    private $flightModel;

    public function __construct($flightModel, $printer){
        $this->flightModel = $flightModel;
        $this->printer = $printer;
    }

    public function execute(){
        $flights = $this->flightModel->getAllFlights();
        $this->printer->generateView('flightView.html', $flights);
    }

    public function getFlights(){
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $flightType = $_POST['flightType'];

        $data = $this->flightModel->getFlights($origin, $destination, $flightType);

        $this->printer->generateView('flightView.html', $data);

    }

    public function searchFlight(){
        $typeFlight = isset($_POST['typeFlight'])? $_POST['typeFlight'] : "";

        if($typeFlight) {
            $flights = $this->flightModel->search($typeFlight);
            echo var_dump($flights);
            $this->printer->generateView('flightView.html', $flights);
        }else{
            $this->execute();
        }


    }
}