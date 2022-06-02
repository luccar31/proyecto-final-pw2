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
        $typeCabin = isset($_POST['$typeCabin'])? $_POST['$typeCabin'] : "";
        $typeService = isset($_POST['$typeService'])? $_POST['$typeService'] : "";

        if($typeFlight) {
            $flights = $this->flightModel->search($typeFlight);
            $this->printer->generateView('flightView.html', $flights);
        }else{
            $this->execute();
        }

        if($typeFlight && $typeCabin){
            $flights = $this->flightModel->search($typeFlight, $typeCabin);
            $this->printer->generateView('flightView.html', $flights);
        }

    }
}