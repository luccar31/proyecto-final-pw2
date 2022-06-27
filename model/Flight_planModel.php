<?php

class Flight_planModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    //funcion que busca vuelos
    public function getFlightPlanList($departure, $destination, $week)
    {

        //como $week viene por ejemplo en formato '2022-W25' tengo que hacer un split y obtener el valor 25 en este caso
        $splitWeek = $this->obtainValuesFromWeekStr($week);
        $week_number = (int)$splitWeek['weekno'];
        $name_destination = $this->getCityNameById($destination);
        $name_departure = $this->getCityNameById($departure);

        echo "Origen: ";
        echo $departure;
        echo "<br>";

        echo "Destino: ";
        echo $destination;
        echo "<br>";

        //obtengo el tipo de vuelo, me retorna en forma de array xq hay veces que para ir a un destino que se repite
        //en recorrido corto y en recorrido largo debemos mostrar ambas opciones
        //hacemos una conversión para que mysql lo pueda interpretar
        $type = $this->consultTypeFlight($departure, $destination);
        echo "Tipo de vuelo que puede elegir: ";
        echo $type;
        echo "<br>";

        $typesOfEquipmentAllowed = $this->consultFlightLevel();
        $typesOfEquipmentAllowed = implode("','", $typesOfEquipmentAllowed);
        echo "Tipo de equipamiento que puede elegir (Orbital, AA, BA): ";
        echo var_dump($typesOfEquipmentAllowed);
        echo "<br>";

        //consulta que muestra todos los planes de vuelo cuando se sacan pasajes desde Buenos Aires o Anakara, aunque
        //haya alguno creado se los muestra igual
        $result = $this->database->query("SELECT fp.id as id, e.model as model, fp.departure_day as day, fp.departure_time as time, tf.description as type, 
                                           te.description as equipment, j.diff_time as hours FROM flight_plan fp
                                           INNER JOIN equipment e on fp.id_equipment = e.id
                                           INNER JOIN days d on fp.departure_day = d.id
                                           INNER JOIN location l on fp.departure_loc = l.id
                                           INNER JOIN type_flight tf on fp.type_flight = tf.id
                                           INNER JOIN type_equipment te on e.id_type = te.id
                                           INNER JOIN route r on tf.id = r.id_type_flight
                                           INNER JOIN journey j on r.id = j.id_route
                                           WHERE tf.id IN ('$type') AND fp.departure_loc = '$departure' AND te.id IN ('$typesOfEquipmentAllowed') 
                                           AND j.id_location = '$destination' AND r.id_type_equipment IN ('$typesOfEquipmentAllowed')
                                           ORDER BY fp.departure_day");



        //metodo para tranformar el dia del plan de vuelo a una fecha en base a la semana elegida por el usuario
        //o dar formato
        $this->mapDate($result, $week);
        $this->calculateArrivalDate($result);

        //consulta para vuelos de origen distintos a Anakara o Buenos Aires, en este caso va a buscar vuelos ya creados.
        //si no encuentra nada, se le muestra un mensaje que no hay vuelos disponibles
        if ($departure > 2) {
            $result = $this->database->query("SELECT f.*, fp.id as id, s1.arrive_date as day, s2.arrive_date as day2, s1.arrive_time as time, s2.arrive_time as time2,
                                                     e.model as model, tf.description  as type, te.description as equipment FROM flight f
                                            INNER JOIN flight_plan fp on f.id_flight_plan = fp.id
                                            INNER JOIN equipment e on fp.id_equipment = e.id
                                            INNER JOIN type_flight tf on fp.type_flight = tf.id
                                            INNER JOIN stop s1 on f.id_flight = s1.id_flight
                                            INNER JOIN stop s2 on f.id_flight = s2.id_flight
                                            INNER JOIN type_equipment te on e.id_type = te.id
                                            WHERE f.departure_week = '$week_number' AND tf.id IN ('$type') AND fp.departure_loc IN (1,2) AND e.id_type IN ('$typesOfEquipmentAllowed')
                                            AND s1.id_location = '$departure' AND s2.id_location = '$destination'");


        }

        //si no encuentra nada, tira mensaje de error
        if (empty($result)) {
            return ['empty' => ['error' => 'No hay vuelos disponibles']];
        }


        //retorno la week para después mandárselo al método que crea el vuelo y así asiganrle el campo 'departure_week'
        return ['flight_plans' => $result, 'week' => $week_number, 'id_destination' => $destination, 'name_destination' => $name_destination , 'name_departure' => $name_departure, 'id_departure' => $departure];
    }

    //función que obtiene el id del tipo de vuelo automáticamente
    private function consultTypeFlight($departure, $destination)
    {

        //obtengo el id de los lugares que hace el recorrido

        //orbital
        $type_flight_1 = $this->database->query("SELECT DISTINCT id_location FROM journey j
                                                 INNER JOIN route r on j.id_route = r.id WHERE r.id_type_flight = 1");

        //circuito corto
        $type_flight_2 = $this->database->query("SELECT DISTINCT id_location FROM journey j
                                                 INNER JOIN route r on j.id_route = r.id WHERE r.id_type_flight = 2");

        //circuito largo
        $type_flight_3 = $this->database->query("SELECT DISTINCT id_location FROM journey j
                                                 INNER JOIN route r on j.id_route = r.id WHERE r.id_type_flight = 3");


        //"limpio" los array para que sean mas facil de leer
        $type_flight_1 = array_column($type_flight_1, 'id_location');
        $type_flight_2 = array_column($type_flight_2, 'id_location');
        $type_flight_3 = array_column($type_flight_3, 'id_location');

        //pregunto si el destino está dentro del respectivo array (recorrido)
        if (in_array($destination, $type_flight_1)) {
            $type = [1];
        } elseif (in_array($destination, $type_flight_2) && !in_array($destination, $type_flight_3)) {
            $type = [2];
        } elseif (in_array($destination, $type_flight_3) && !in_array($destination, $type_flight_2)) {
            $type = [3];
            //si el destino está en el circuito corto y en el circuito largo que me retorne un array con los dos tipos
        } elseif (in_array($destination, $type_flight_2) && in_array($destination, $type_flight_3)) {
            $type = [2, 3];
        } else {
            $type = [4];
        }

        //en el caso de Orbital Hotel, si el origen existe en circuito corto pero no en largo, automaticamente
        //lo asigna como tipo circuito corto
        if (!in_array($departure, $type_flight_3) && in_array($departure, $type_flight_2)) {
            $type = [2];
        }


        //el implode me retorna algo asi (1,2,3)
        return implode("','", $type);
    }

    //función para crear el vuelo
    public function createFlight($id_flight_plan, $departure_date, $departure_time, $departure_id, $week)
    {
        $ship = $this->getAvailableShip($id_flight_plan);

        if (!$ship) {
            return 'Error';
        }

        //creo fecha
        $datetime = date_create($departure_date . " " . $departure_time);
        $date = date_format($datetime, 'Y-m-d');
        $time = date_format($datetime, 'H:i:s');

        //consulto si ya existe el vuelo
        $createdFlight = $this->database->query("SELECT id_flight FROM flight WHERE id_flight_plan = '$id_flight_plan'");

        //si existe el vuelo, se toma el mismo id. Sino, se crea otro
        if (empty($createdFlight)) {
            $id_flight = $this->generateIdFlight(); //genero un entero random para el id del vuelo
        } else {
            $id_flight = $createdFlight[0]['id_flight'];
        }


        if (empty($createdFlight)) {

            //creo el vuelo
            $this->database->query("INSERT INTO flight (id_flight ,id_flight_plan, id_ship, departure_date, departure_hour, departure_week)
                                VALUES ('$id_flight','$id_flight_plan','$ship[id]','$date','$time', '$week')
                                ");

            //creo las escalas
            $this->createStops($id_flight_plan, $id_flight, $departure_date, $departure_time, $departure_id, 'asc'); //creo sus escalas en el orden comun
        }


        return $id_flight;
    }

    //consulta el nivel de vuelo del cliente
    private function consultFlightLevel()
    {
        //si inició sesión:
        if (isset($_SESSION["nickname"])) {
            $nickname = $_SESSION["nickname"];

            //busca el nivel de vuelo del cliente
            $flight_level = $this->database->query("SELECT flight_level FROM client WHERE user_nickname = '$nickname'");

            //si no hizo chequeo médico, que muestre all igual, total en la ultima etapa le decimos que no hizo el chequeo:
            if (empty($flight_level)) {
                return [1, 2, 3];
                //si realizó el chequeo, evaluo el nivel de vuelo
            } else {
                // si es nivel 1 o 2, puede viajar en circuito corto y largo (1, 2)
                if ($flight_level[0]["flight_level"] == 1 || $flight_level[0]["flight_level"] == 2) {
                    return [1, 2];
                    //de lo contrario es nive 3, puede viajar en todos
                } else {
                    return [1, 2, 3];
                }
            }
        } //si no inició sesión, que muestre all tipos total en la ultima etapa le decimos que no hizo el chequeo.
        else {
            return [1, 2, 3];
        }
    }

    //valida los inputs del formulario
    public function validateInputs($departure, $destination, $week)
    {
        $week = isset($week) ? $week : null;
        $errors = [];

        //consulto el tipo de vuelo
        $type = $this->consultTypeFlight($departure, $destination);

        //busco las ciudades según el tipo de vuelo
        $existingLocation = $this->database->query("SELECT DISTINCT id_location FROM journey j
                                                       INNER JOIN route r on j.id_route = r.id WHERE r.id_type_flight IN ('$type')");

        //formula para dejarlo mas limpio el array :P
        $existingLocation = array_column($existingLocation, 'id_location');

        //si el origen el orbital hotel y el destino está en circuito largo, error
        if ($departure == 4 && !in_array($destination, $existingLocation)) {
            $errors['invalidJourney'] = "No ofrecemos ese trayecto actualmente";
        }
        //si origen y destino son iguales, error
        if ($departure == $destination) {
            $errors['sameLocations'] = "El origen y destino no pueden ser el mismo";
        }

        if ($this->getStopsOrder($departure, $destination) == false){
            $errors['badOrder'] = "El origen que eligió es posterior al destino";
        }

        //semana anterior a la actual, error
        if ($week != null) {

            $currentDate = new DateTime();
            $currentWeek = $currentDate->format("W");
            $splitWeek = $this->obtainValuesFromWeekStr($week);
            $week_number = (int)$splitWeek['weekno'];

            if ($week_number < $currentWeek) {
                $errors['invalidWeek'] = "La semana que seleccionó ya pasó";
            }
        }
        return $errors;
    }

    private function getRoute($id_flight_plan)
    {
        $plan = $this->getPlan($id_flight_plan);
        $equipment = $this->getEquipment($plan["id_equipment"]);

        $route = $this->database->query("SELECT * FROM route WHERE id_type_equipment = '$equipment[id_type]' AND id_type_flight = '$plan[type_flight]'");

        return $route[0];
    }

    private function getJourney($route_id, $order, $origin)
    {
        return $this->database->query("SELECT * FROM journey WHERE id_route = '$route_id' AND id_location <> '$origin' ORDER BY order_ $order");
    }

    private function createStops($id_flight_plan, $id_flight, $departure_date, $departure_time, $departure_id, $order)
    {

        $route = $this->getRoute($id_flight_plan); //obtengo el registro de la ruta
        $journey = $this->getJourney($route['id'], $order, $departure_id); //obtengo el recorrido que sigue esa ruta en un determinado orden

        //creo fecha
        $d = date_create($departure_date . " " . $departure_time);
        $time = date_format($d, 'h:i:s');
        $date = date_format($d, 'Y-m-d');

        //inserto la primer escala que es el origen
        $this->database->query("INSERT INTO stop (id_flight, id_location, arrive_time, arrive_date)
                                    VALUES ('$id_flight','$departure_id','$time','$date')
                                   ");

        //inserto todas las demas escalas
        foreach ($journey as $stop) {

            $hours = $stop['diff_time'];
            date_add($d, date_interval_create_from_date_string("$hours hours"));

            $time = date_format($d, 'h:i:s');
            $date = date_format($d, 'Y-m-d');

            $this->database->query("INSERT INTO stop (id_flight, id_location, arrive_time, arrive_date)
                                    VALUES ('$id_flight','$stop[id_location]','$time','$date')
                                   ");
        }

    }

    private function getAvailableShip($id_flight_plan)
    {
        $plan = $this->getPlan($id_flight_plan);
        $ship = $this->database->query("SELECT * FROM ship WHERE id_equipment=$plan[id_equipment] AND available = true");
        return $ship[0];
    }

    private function getPlan($id_flight_plan)
    {
        $plan = $this->database->query("SELECT * FROM flight_plan WHERE id='$id_flight_plan'");
        return $plan[0];
    }

    public function getTypeFlights()
    {
        return $this->database->query("SELECT * FROM type_flight");
    }

    public function getCities()
    {
        return $this->database->query("SELECT id, name from location");
    }

    //esto es para mostrar devuelta en la vista luego de algun error
    public function getCityNameById($id)
    {
        $city = $this->database->query("SELECT name from location WHERE id = '$id'");
        if (!empty($city)) {
            return $city[0]['name'];
        }

    }

   /* public function getDestinationCities($departure){
        return $this->database->query("SELECT id, name from location");
    }*/

    private function mapDate(&$array, $week)
    {
            $res = $this->obtainValuesFromWeekStr($week);
            $year = (int)$res['year'];
            echo $year;
            $week_no = (int)$res['weekno'];

            $date = new DateTime();

            foreach ($array as &$register) {
                $register['day'] = $date->setISODate($year, $week_no, (int)$register['day'] + 1)->format('Y-m-d');
            }

    }

    private function obtainValuesFromWeekStr($str)
    {
        $matches = [];
        preg_match('/(?<year>\d{4})-W(?<weekno>\d{1,2})/', $str, $matches);
        return $matches;
    }

    private function getFlightById($id_flight)
    {
        return $this->database->query("SELECT * FROM flight WHERE id_flight = '$id_flight'")[0];
    }

    private function generateIdFlight()
    {
        return rand(100000, 999999);
    }

    private function getEquipment($id_equipment)
    {
        $equipment = $this->database->query("SELECT * FROM equipment WHERE id = '$id_equipment'");
        return $equipment[0];
    }

    //en el caso que no haya vuelo existente, se tiene que convertir el dia (L,M,X,J,V,S,D) en un formato fecha y calcular
    //con las horas
    public function calculateArrivalDate(&$result)
    {

        foreach ($result as &$flight){
            // le paso el dia y la hora
            $dateTime =  $flight['day'] . $flight['time'];
            // este metodo me crea la fecha con esos parámetros :)
            $dateTime = date_create($dateTime);
            $hours = $flight['hours'];
            //con este método le sumo las horas a la fecha
            $calculatedDateTime = date_add($dateTime, date_interval_create_from_date_string("$hours hours"));
            $flight['day2'] = $calculatedDateTime->format('Y-m-d');
            $flight['time2'] = $calculatedDateTime->format('H:i:s');
        }

    }

    //metodo para buscar donde esta la nave, sirve para la barra de progreso
    public function findShipPosition($id_ship){

        //fecha y hora actual
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $actualDate = date('Y-m-d');
        $actualTime = date(' H:i:s');

        $actualDate = '2022-07-6';
        $actualTime = '09:00:00';

        $actualDateTime = $actualDate . " " . $actualTime;

        //busco de la tabla stop, aquellos relacionados a un vuelo que utilice la nave. Obtengo sus fechas y hora de llegada y las concateno
        //para después compararlos con la fecha y hora actual. Solo quiero aquellos resultados anteriores a la fecha actual.
        //Me los trae en orden descentende.
       $position = $this->database->query("SELECT (CONCAT(s.arrive_date, ' ', s.arrive_time)) as dateTime, s.id_location, s2.id_location as lastLocation FROM stop s
                               INNER JOIN flight f on s.id_flight = f.id_flight
                               INNER JOIN stop s2 on s.id_flight = s2.id_flight

                               WHERE f.id_ship = '$id_ship' AND s.arrive_date in (SELECT max(arrive_date) FROM stop WHERE arrive_date <= '$actualDate')
                               AND s.arrive_time in (SELECT arrive_time FROM stop WHERE arrive_time >= '$actualTime' AND arrive_time >= (SELECT min(arrive_time) FROM stop WHERE arrive_date = s.arrive_date))
                               AND s2.id_location = (SELECT max(id_location) from stop WHERE id_location < s.id_location)
                               ORDER BY s.arrive_time asc ");


       if (!empty($position)){
           if ($position[0]['dateTime'] > $actualDateTime){
               return $position[0]['lastLocation'];
           }
           else{
               return $position[0]['id_location'];
           }
       }
       else{

           $lastPosition = $this->database->query("SELECT max(id_location) as id_location FROM stop WHERE arrive_date <= '$actualDate'");

           return $lastPosition[0]['id_location'];
       }






    }

    private function getStopsOrder($departure, $destination)
    {
        $departureOrder = $this->database->query("SELECT id from location WHERE id = '$departure'");
        $destinationOrder = $this->database->query("SELECT id from location WHERE id = '$destination'");

        if ($destinationOrder < $departureOrder){
            return false;
        }
        else{
            return true;
        }
    }

}