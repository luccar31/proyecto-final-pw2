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
        $data['selectedDepartureName'] = $this->flight_planModel->getCityNameById($data['departure']);
        $data['selectedDestinationName'] = $this->flight_planModel->getCityNameById($data['destination']);
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
        $data = $this->flight_planModel->getFlightPlanList($departure, $destination, $week);

        $this->printer->generateView('flightPlanSearchView.html', $data);
    }

    public function showFlightDetail(){
        $data['id_flight_plan'] = $_GET["id"];
        $data['departure_date'] = $_GET["date"];
        $data['departure_time'] = $_GET["time"];
        $data['arrival_date'] = $_GET["date2"];
        $data['arrival_time'] = $_GET["time2"];
        $data['departure'] = $_GET["departure"];
        $data['destination'] = $_GET["destination"];
        $data['week'] = $_GET["week"];
        $data['hours'] = $_GET["hours"];

        $this->printer->generateView('flight_detail.html', $data);

    }



    // una vez elegido el vuelo, se evalua si inició sesion y si hizo o no el chequeo médico. Caso exitoso: reserva el vuelo
    public function flight_planConfirmation()
    {
        $id_flight_plan = $_GET["id"];
        $departure_date = $_GET["date"];
        $departure_time = $_GET["time"];
        $departure = $_GET["departure"];
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

      /*  $this->printer->generateView('flight_detail.html', $data);*/


    }

    //esto es para la barra de progreso dinámica (esta harcodeado)
    public function progress()
    {

        $id_ship = $_GET['id_ship'];

        $data['circuitoLargo'] = ['3' => 'disabled3', '5' => 'disabled5', '6' => 'disabled6', '7' => 'disabled7',
            '8' => 'disabled8', '9' => 'disabled9', '10' => 'disabled10', '11' => 'disabled11'];

        $data['circuitoCorto'] = ['3' => 'disabled3', '4' => 'disabled4', '5' => 'disabled5', '6' => 'disabled6'];


        $id_position = $this->flight_planModel->findShipPosition($id_ship);


        for ($i = 3; $i<=$id_position; $i++){

                    $data['circuitoLargo'][$i] = "active" . $i . " active";
        }

        $data['circuitoLargo']['lastPosition'.$id_position] = $id_position;



        return$this->printer->generateView('flightStatus.html', $data);


    }


}