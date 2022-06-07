<?php

class Flight_planController{
    private $printer;
    private $flight_planModel;

    public function __construct($flight_planModel, $printer){
        $this->flight_planModel = $flight_planModel;
        $this->printer = $printer;
    }

    public function execute(){
        $flights = $this->flight_planModel->getAllFlight_plans();
        $this->printer->generateView('flight_planView.html', $flights);
    }


    public function searchFlight(){
        $typeFlight = isset($_POST['typeFlight'])? $_POST['typeFlight'] : "";

        if($typeFlight) {
            $flights = $this->flight_planModel->search($typeFlight);
            $this->printer->generateView('flight_planView.html', $flights);
        }else{
            $this->execute();
        }


    }

    public function flight_planConfirmation(){
        $id_flight_plan = $_GET["id"];
        $flight_plan = $this->flight_planModel->search($id_flight_plan);
        $this->printer->generateView('flight_planConfirmation.html', $flight_plan);
    }

}