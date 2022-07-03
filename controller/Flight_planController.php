<?php

class Flight_planController
{
    private $printer;
    private $flight_planModel;

    public function __construct($models, $printer)
    {
        $this->flight_planModel = $models['flight_planModel'];
        $this->printer = $printer;
    }

    //lanza la vista con las ciudades para elegir
    public function execute()
    {

        //con esta variable evitamos que puedan comprar muchos tickets al recargar la página una vez que
        //está hecha la reserva. En ese momento le seteamos createTicketComplete a true y no podrá comprar hasta que
        //realice el proceso nuevamente. Por eso, en la primer etapa, lo ponemos en false para que pueda buscar.
        $_SESSION['createTicketComplete'] = false;


        $data['type'] = $_POST['type'];
        $data['departureCities'] = $this->flight_planModel->getDepartureCities($data['type']);
        $data['destinationCities'] = $this->flight_planModel->getDestinationCities($data['type']);
        $this->printer->generateView('flightPlanFormView.html', $data);
    }

    //muestra el formulario y lo valida
    public function searchFlightForm()
    {
        $data['type'] = $_POST['type'];
        $_SESSION['type'] = $_POST['type'];
        $data['departureCities'] = $this->flight_planModel->getDepartureCities($data['type']);
        $data['destinationCities'] = $this->flight_planModel->getDestinationCities($data['type']);
        $data['departure'] = $_POST['departure'];
        $data['destination'] = $_POST['destination'];
        $data['week'] = $_POST['week'];
        $data['selectedDepartureName'] = $this->flight_planModel->getCityNameById($data['departure']);
        $data['selectedDestinationName'] = $this->flight_planModel->getCityNameById($data['destination']);
        $errors = 0;

        $this->validateInputs($data['week'], $data['departure'], $data['destination'], $data, $errors);

        if ($errors != 0) {
            $this->printer->generateView('flightPlanFormView.html', $data);
            exit();
        }

        $data['errors'] = $this->flight_planModel->validateInputs($data['departure'], $data['destination'], $data['week'], $data['type']);

        if (!empty($data['errors'])) {
            $this->printer->generateView('flightPlanFormView.html', $data);
            exit();
        }

        $this->searchFlight($data['departure'], $data['destination'], $data['week'], $data['type']);
    }

    //busca vuelos creados o planes de vuelo
    private function searchFlight($departure, $destination, $week, $type)
    {
        $data = $this->flight_planModel->getFlightPlanList($departure, $destination, $week, $type);

        $this->printer->generateView('flightPlanSearchView.html', $data);
    }

    //esto es para la barra de progreso dinámica
    public function progress()
    {

        $id_ship = $_GET['id_ship'];
        $id_type_flight = $_GET['id_type_flight'];

        //busca la posición actual de la nave según la fecha y hora
        $id_position = $this->flight_planModel->findShipPosition($id_ship);

        if ($id_type_flight == 1) {

            $data['orbital'][1] = "active" . 1 . " active";
            $data['orbital'][2] = "active" . 2 . " active";
            $data['orbital']['lastPosition' . $id_position] = $id_position;

        } elseif ($id_type_flight == 2) {

            $data['circuitoCorto'] = ['3' => 'disabled3', '4' => 'disabled4', '5' => 'disabled5', '6' => 'disabled6'];

            for ($i = 3; $i <= $id_position; $i++) {
                $data['circuitoCorto'][$i] = "active" . $i . " active";
            }

            $data['circuitoCorto']['lastPosition' . $id_position] = $id_position;

        } else {

            $data['circuitoLargo'] = ['3' => 'disabled3', '5' => 'disabled5', '6' => 'disabled6', '7' => 'disabled7',
                '8' => 'disabled8', '9' => 'disabled9', '10' => 'disabled10', '11' => 'disabled11'];

            for ($i = 3; $i <= $id_position; $i++) {
                $data['circuitoLargo'][$i] = "active" . $i . " active";
            }

            $data['circuitoLargo']['lastPosition' . $id_position] = $id_position;
        }

        return $this->printer->generateView('flightStatus.html', $data);
    }

    private function validateInputs($week, $departure, $destination, &$data, &$errors){
        // semana vacía
        if (!$week) {
            $errors++;
            $data['emptyWeek'] = "Seleccione una semana";
        }
        // origen vacío
        if (!$departure) {
            $errors++;
            $data['emptyDepartureError'] = "Seleccione un origen";
        }
        // destino vacío
        if (!$destination) {
            $errors++;
            $data['emptyDestinationError'] = "Seleccione un destino";
        }
    }
}