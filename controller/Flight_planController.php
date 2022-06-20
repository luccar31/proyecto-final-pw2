<?php

class Flight_planController
{
    private $printer;
    private $flight_planModel;
    private $appointmentModel;
    public function __construct($models, $printer)
    {
        $this->flight_planModel = $models['flight_planModel'];
        $this->appointmentModel = $models['appointmentModel'];
        $this->printer = $printer;
    }

    public function execute()
    {
        Helper::redirect("/flight_plan/searchFlightForm");
    }

    //muestra la vista
    public function searchFlightForm()
    {

        $data['cities'] = $this->flight_planModel->getCities();
        $data['enabledClient'] = $this->appointmentModel->getAppointment($_SESSION['nickname']);
        /*$data['errors'] = isset($_SESSION['errors']) ? $_SESSION['errors'] : null;
        unset($_SESSION['errors']);*/

        $this->printer->generateView('flightPlanFormView.html', $data);
    }

    //busca vuelos creados o planes de vuelo
    public function searchFlight()
    {
        $departure = $_SESSION['departure'] = $_POST['departure'];
        $destination = $_SESSION['destination'] = $_POST['destination'];
        $week = $_SESSION['week'] = $_POST['week'];

        $flightPlanList = $this->flight_planModel->getFlightPlanList($departure, $destination, $week);


        $this->printer->generateView('flightPlanSearchView.html', $flightPlanList);
    }

    //toma los datos y en caso de que no exista el vuelo lo crea y me devuelve el detalle de lo que reservé
    public function flight_planConfirmation()
    {
        $id_flight_plan = $_GET["id"];
        $departure_date = $_GET["date"];
        $departure_time = $_GET["time"];
        $departure = $_GET["depart"];
        $week = $_GET["week"];

        $id_flight = $this->flight_planModel->createFlight($id_flight_plan, $departure_date, $departure_time, $departure, $week);

        $this->printer->generateView('flight_detail.html', $id_flight);

    }

    //esto es para la barra de progreso dinámica (esta harcodeado)
    public function progress()
    {

        $flight_type = 3;
        $progress = 0;
        $ubication = 'Marte';

        if ($flight_type == 2) {

            if ($ubication == 'Ankara' || $ubication == 'Buenos Aires') {
                $progress = 9.5;
            } elseif ($ubication == 'EEI') {
                $progress = 25;
            } elseif ($ubication == 'OrbitalHotel') {
                $progress = 50;
            } elseif ($ubication == 'Luna') {
                $progress = 75;
            } elseif ($ubication == 'Marte') {
                $progress = 100;
            }

            $data = ['progress' => $progress, 'flight_type_2' => true, 'flight_type_3' => false];
        } elseif ($flight_type == 3) {

            if ($ubication == 'Ankara' || $ubication == 'Buenos Aires') {
                $progress = 5;
            } elseif ($ubication == 'EEI') {
                $progress = 12.5;
            } elseif ($ubication == 'Luna') {
                $progress = 25;
            } elseif ($ubication == 'Marte') {
                $progress = 37.5;
            } elseif ($ubication == 'Ganimedes') {
                $progress = 50;
            } elseif ($ubication == 'Europa') {
                $progress = 62.5;
            } elseif ($ubication == 'Io') {
                $progress = 75;
            } elseif ($ubication == 'Encedalo') {
                $progress = 87.5;
            } elseif ($ubication == 'Titan') {
                $progress = 100;
            }

            $data = ['progress' => $progress, 'flight_type_2' => false, 'flight_type_3' => true];
        }

        $this->printer->generateView('flightStatus.html', $data);


    }

    /*private function formValidationStep1($type, $week)
    {
        $error = [];

        if (!$this->isValidWeek($week)) {
            $error[] = 'Ingrese una semana válida';
        }

        if (!$this->isValidSelectInput($type)) {
            $error[] = 'Ingrese un tipo de vuelo válido';
        }

        return $error;
    }

    private function formValidationStep2($departure, $destination)
    {
        $error = [];

        if (!$this->isValidSelectInput($departure)) {
            $error[] = 'Ingrese una ciudad de origen válida';
        }

        if (!$this->isValidSelectInput($destination)) {
            $error[] = 'Ingrese una ciudad de destino válida';
        }

        return $error;
    }

    private function isValidWeek($input = true)
    {
        return $input;
    }

    private function isValidSelectInput($input)
    {
        return $input != 0;
    }*/
}