<?php

class Flight_planController{
    private $printer;
    private $flight_planModel;

    public function __construct($flight_planModel, $printer){
        $this->flight_planModel = $flight_planModel;
        $this->printer = $printer;
    }

    public function execute(){
        Helper::redirect("/flight_plan/searchFlightFormStep1");
    }

    public function searchFlightFormStep1(){

        $data['typeFlights'] = $this->flight_planModel->getTypeFlights();
        $data['week'] = isset($_SESSION['week']) ? $_SESSION['week'] : null;
        $data['errors'] = isset($_SESSION['errors']) ? $_SESSION['errors'] : null;
        unset($_SESSION['errors']);

        $this->printer->generateView('flight_planFormStep1View.html', $data);
    }

    public function validateStep1(){
        $_SESSION['week'] = $_POST['week'];
        $_SESSION['type'] = $_POST['type'];
        //validacion de inputs
        Helper::redirect('/flight_plan/searchFlightFormStep2');
    }

    public function searchFlightFormStep2(){

        $data['cities'] = $this->flight_planModel->getCities($_SESSION['type']);
        $data['errors'] = isset($_SESSION['errors']) ? $_SESSION['errors'] : null;
        unset($_SESSION['errors']);

        $this->printer->generateView('flight_planFormStep2View.html', $data);
    }
    
    public function validateStep2(){
        $_SESSION['depart'] = $_POST['depart'];
        $_SESSION['dest'] = $_POST['dest'];
        //validacion de inputs
        //habria que validar que haya pasado por el paso 1
        Helper::redirect("/flight_plan/searchFlight");
    }

    public function searchFlight(){
        $type = isset($_SESSION['type']) ? $_SESSION['type'] : null;
        $departure = isset($_SESSION['depart']) ? $_SESSION['depart'] : null;
        $destination = isset($_SESSION['dest']) ? $_SESSION['dest'] : null;
        $week = isset($_SESSION['week']) ? $_SESSION['week'] : null;


        $response = $this->flight_planModel->getPlansOrFlight($type, $departure, $week, $destination);

        //validacion de errores del modelo

        $this->printer->generateView('flight_planSearchView.html', $response);
    }

    private function formValidationStep1($type, $week){
        $error = [];

        if(!$this->isValidWeek($week)){
            $error[] = 'Ingrese una semana válida';
        }

        if(!$this->isValidSelectInput($type)){
            $error[] = 'Ingrese un tipo de vuelo válido';
        }

        return $error;
    }

    private function formValidationStep2($departure, $destination){
        $error = [];

        if(!$this->isValidSelectInput($departure)){
            $error[] = 'Ingrese una ciudad de origen válida';
        }

        if(!$this->isValidSelectInput($destination)){
            $error[] = 'Ingrese una ciudad de destino válida';
        }

        return $error;
    }

    private function isValidWeek($input = true){
        return $input;
    }

    private function isValidSelectInput($input){
        return $input != 0;
    }

    public function flight_planConfirmation(){
        $id_flight_plan = $_GET["id"];
        $departure_date = $_GET["date"];
        $departure_time = $_GET["time"];
        $departure = $_GET["depart"];

        $flight = $this->flight_planModel->createFlight($id_flight_plan, $departure_date, $departure_time, $departure);

        Helper::redirect('/flight_plan/searchFlightFormStep1');

        $flight_plan = $this->flight_planModel->searchForId($id_flight_plan);
        $this->printer->generateView('flight_planConfirmation.html', $flight_plan);
    }
}