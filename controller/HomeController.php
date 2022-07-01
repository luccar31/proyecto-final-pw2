<?php

class HomeController{

    private $printer;
    private $flight_planModel;

    public function __construct($printer, $flight_planModel) {
        $this->printer = $printer;
        $this->flight_planModel = $flight_planModel;
    }

    public function execute() {

        $this->printer->generateView('homeView.html');
    }

}