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
        $this->printer->generateView('flightView.html');
    }


}