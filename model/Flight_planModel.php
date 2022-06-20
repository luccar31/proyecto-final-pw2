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
        $types = implode("','", $type);
        echo "Tipo de vuelo que puede elegir: ";
        echo var_dump($type);
        echo "<br>";

        $typesOfEquipmentAllowed = $this->consultFlightLevel();
        $typesOfEquipmentAllowed = implode("','", $typesOfEquipmentAllowed);
        echo "Tipo de equipamiento que puede elegir (Orbital, AA, BA): ";
        echo var_dump($typesOfEquipmentAllowed);
        echo "<br>";

        //consulta que muestra todos los planes de vuelo cuando se sacan pasajes desde Buenos Aires o Anakara, aunque
        //haya alguno creado se los muestra igual

        $result = $this->database->query("SELECT fp.id as id, e.model as model, fp.departure_day as day, l.name as departure, tf.description as type, fp.departure_time as time FROM flight_plan fp
                                           INNER JOIN equipment e on fp.id_equipment = e.id
                                           INNER JOIN days d on fp.departure_day = d.id
                                           INNER JOIN location l on fp.departure_loc = l.id
                                           INNER JOIN type_flight tf on fp.type_flight = tf.id
                                           WHERE tf.id IN ('$types') AND l.id = '$departure' AND e.id_type IN ('$typesOfEquipmentAllowed')");


        //consulta para vuelos de origen distintos a Anakara o Buenos Aires, en este caso va a buscar vuelos ya creados.
        //si no encuentra nada, se le muestra un mensaje que no hay vuelos disponibles
        if ($departure > 2) {
            $result = $this->database->query("SELECT f.*, fp.departure_day as day, fp.id as id, fp.departure_time as time, l.name as departure, e.model as model, tf.description as type FROM flight f
                                            INNER JOIN flight_plan fp on f.id_flight_plan = fp.id
                                            INNER JOIN location l on fp.departure_loc = l.id
                                            INNER JOIN equipment e on fp.id_equipment = e.id
                                            INNER JOIN type_flight tf on fp.type_flight = tf.id
                                            INNER JOIN stop s on f.id_flight = s.id_flight
                                            WHERE f.departure_week = '$week_number' AND tf.id IN ('$types') AND fp.departure_loc IN (1,2) AND s.id_location = '$destination'");

        }
        if (empty($result) || $departure < 2) {
            return ['empty' => ['error' => 'No hay vuelos disponibles']];
        }

        //metodo para tranformar el dia del plan de vuelo a una fecha en base a la semana elegida por el usuario
        $this->mapDate($result, $week);

        //retorno la week para después mandárselo al método que crea el vuelo y así asiganrle el campo 'departure_week'

        return ['flight_plans' => $result, 'week' => $week_number];
    }

    //función que obtiene el id del tipo de vuelo automáticamente
    private function consultTypeFlight($departure, $destination)
    {

        // cada array almacena el id del los destinos

        //orbital
        $type_flight_1 = ['Buenos Aires' => 1, 'Ankara' => 2];

        //circuito corto
        $type_flight_2 = ['Buenos Aires' => 1, 'Ankara' => 2, 'EEI' => 3, 'Orbital Hotel' => 4, 'Luna' => 5, 'Marte' => 6];

        //circuito largo
        $type_flight_3 = ['Buenos Aires' => 1, 'Ankara' => 2, 'EEI' => 3, 'Luna' => 5, 'Marte' => 6, 'Ganimedes' => 7, 'Europa' => 8, 'Io' => 9, 'Encedalo' => 10, 'Titan' => 11];

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

        if (in_array($departure, $type_flight_2) && !in_array($departure, $type_flight_3)) {
            $type = [2];
        }

        return $type;
    }

    //función para crear el vuelo
    public function createFlight($id_flight_plan, $departure_date, $departure_time, $departure, $week)
    {
        $ship = $this->getAvailableShip($id_flight_plan);

        if (!$ship) {
            return 'Error';
        }

        //creo fecha
        $datetime = date_create($departure_date . " " . $departure_time);
        $date = date_format($datetime, 'Y-m-d');
        $time = date_format($datetime, 'H:m:s');

        //consulto si ya existe el vuelo
        $createdFlight = $this->database->query("SELECT id_flight FROM flight WHERE id_flight_plan = '$id_flight_plan' AND departure_date = '$date' AND departure_hour = '$time'");

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

            $this->createStops($id_flight_plan, $id_flight, $departure_date, $departure_time, $departure, 'asc'); //creo sus escalas en el orden comun
        }


        return ['id_flight' => $id_flight];;
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

    private function createStops($id_flight_plan, $id_flight, $departure_date, $departure_time, $name_origin, $order)
    {

        $origin = $this->getLocationByName($name_origin); //obtengo el registro de la locacion de origen
        $route = $this->getRoute($id_flight_plan); //obtengo el registro de la ruta
        $journey = $this->getJourney($route['id'], $order, $origin['id']); //obtengo el recorrido que sigue esa ruta en un determinado orden

        //creo fecha
        $d = date_create($departure_date . " " . $departure_time);
        $time = date_format($d, 'H:i:s');
        $date = date_format($d, 'Y-m-d');

        //inserto la primer escala que es el origen
        $this->database->query("INSERT INTO stop (id_flight, id_location, arrive_time, arrive_date)
                                    VALUES ('$id_flight','$origin[id]','$time','$date')
                                   ");

        //inserto todas las demas escalas
        foreach ($journey as $stop) {

            date_modify($d, "+" . $stop['diff_time'] . "hours");

            $time = date_format($d, 'H:i:s');
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

    private function mapDate(&$array, $week)
    {

        $res = $this->obtainValuesFromWeekStr($week);
        $year = (int)$res['year'];
        $week_no = (int)$res['weekno'];

        $date = new DateTime();

        foreach ($array as &$register) {
            $register['day'] = $date->setISODate($year, $week_no, (int)$register['day'] + 1)->format('d-m-Y');
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

    private function getLocationByName($name)
    {
        $location = $this->database->query("SELECT * FROM location WHERE name = '$name'");
        return $location[0];
    }

    private function consultFlightLevel()
    {
        $nickname = $_SESSION["nickname"];
        $flight_level = $this->database->query("SELECT flight_level FROM client WHERE user_nickname = '$nickname'");


        if ($flight_level[0]["flight_level"] == 1 || $flight_level[0]["flight_level"] == 2) {
            return [1, 2];
        } else {
            return [1, 2, 3];
        }
    }

}