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

        $data['cities'] = $this->flight_planModel->getCities();
        $this->printer->generateView('flightPlanFormView.html', $data);
    }

    //muestra el formulario y lo valida
    public function searchFlightForm()
    {
        $data['cities'] = $this->flight_planModel->getCities();
        $data['departure'] = $_POST['departure'];
        $data['destination'] = $_POST['destination'];
        $data['week'] = $_POST['week'];
        $data['selectedDepartureName'] = $this->flight_planModel->getCitieNameById($data['departure']);
        $data['selectedDestinationName'] = $this->flight_planModel->getCitieNameById($data['destination']);
        $errors = 0;


        // semana vacía
        if (empty($data['week'])) {
            $errors++;
            $data['emptyWeek'] = "Seleccione una semana";
        }
        // origen vacío
        if (empty($data['departure'])) {
            $errors++;
            $data['emptyDepartureError'] = "Seleccione un origen";
        }
        // destino vacío
        if (empty($data['destination'])) {
            $errors++;
            $data['emptyDestinationError'] = "Seleccione un destino";
        }

        //si los inputs están todos seteados, se evalua en el backend:
        if ($errors == 0) {

            //me devuelve errores del backend en caso de que los haya
            $data['errors'] = $this->flight_planModel->validateInputs($data['departure'], $data['destination'], $data['week']);

            //si lo devuelto está vacio, no hay errores
            if (empty($data['errors'])) {

                //por lo tanto, busca los vuelos para mostrar
                $this->searchFlight($data['departure'], $data['destination'], $data['week']);

            } //caso contrario, hay errores (semana antigua, origen y destino igual), vuelve al formulario
            else {
                $this->printer->generateView('flightPlanFormView.html', $data);
            }

        } //si sigue habiendo erroes en los input, vuelve al formulario
        else {
            $this->printer->generateView('flightPlanFormView.html', $data);
        }
    }

    //busca vuelos creados o planes de vuelo
    private function searchFlight($departure, $destination, $week)
    {
        $flightPlanList = $this->flight_planModel->getFlightPlanList($departure, $destination, $week);

        $this->printer->generateView('flightPlanSearchView.html', $flightPlanList);
    }

    // una vez elegido el vuelo, se evalua si inició sesion y si hizo o no el chequeo médico. Caso exitoso: reserva el vuelo
    public function flight_planConfirmation()
    {
        $id_flight_plan = $_GET["id"];
        $departure_date = $_GET["date"];
        $departure_time = $_GET["time"];
        $departure = $_GET["depart"];
        $week = $_GET["week"];

        if (isset($_SESSION['nickname'])) {

            $data['enabledClient'] = $this->appointmentModel->getAppointment($_SESSION['nickname']);

            //inició sesión pero no realizó chequeo médico
            if (empty($data['enabledClient'])) {

                $data['disabledClient'] = "Debe realizar un chequeo médico. El código de viajero y nivel de vuelo es requerido.";

            } //inició sesión y realizó chequeo médico. Hace la reserva.
            else {

                $data['id_flight'] = $this->flight_planModel->createFlight($id_flight_plan, $departure_date, $departure_time, $departure, $week);
                $data['enabledClient'] = "Su vuelo ha sido reservado! N° de vuelo: ";

            }

        } //no inició sesión
        else {
            $data['notLogged'] = "Debe inciar sesión para reservar vuelos";
        }

        $this->printer->generateView('flight_detail.html', $data);


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

}