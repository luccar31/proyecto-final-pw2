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


        //obtengo el tipo de vuelo, me retorna en forma de array xq hay veces que para ir a un destino que se repite
        //en recorrido corto y en recorrido largo debemos mostrar ambas opciones
        //hacemos una conversión para que mysql lo pueda interpretar
        $type = $this->consultTypeFlight($destination);
        $type = implode("','", $type);


        //consulta que muestra todos los planes de vuelo cuando se sacan pasajes desde Buenos Aires o Anakara, aunque
        //haya alguno creado se los muestra igual
        $result = $this->database->query("SELECT fp.id as id, e.model as model, fp.departure_day as day, l.name as departure, tf.description as type, fp.departure_time as time FROM flight_plan fp
                                           INNER JOIN equipment e on fp.id_equipment = e.id
                                           INNER JOIN days d on fp.departure_day = d.id
                                           INNER JOIN location l on fp.departure_loc = l.id
                                           INNER JOIN type_flight tf on fp.type_flight = tf.id
                                           WHERE tf.id IN ('$type') AND l.id = '$departure'");


        //consulta para vuelos de origen distintos a Anakara o Buenos Aires, en este caso va a buscar vuelos ya creados.
        //si no encuentra nada, se le muestra un mensaje que no hay vuelos disponibles
        if ($departure > 2) {
            $result = $this->database->query("SELECT f.*, fp.departure_day as day, fp.id as id, fp.departure_time as time, l.name as departure, e.model as model, tf.description as type FROM flight f
                                            INNER JOIN flight_plan fp on f.id_flight_plan = fp.id
                                            INNER JOIN location l on fp.departure_loc = l.id
                                            INNER JOIN equipment e on fp.id_equipment = e.id
                                            INNER JOIN type_flight tf on fp.type_flight = tf.id
                                            WHERE f.departure_week = '$week_number' AND tf.id IN ('$type') AND fp.departure_loc IN (1,2)");

            if (empty($result)) {
                return ['empty' => ['error' => 'No hay vuelos disponibles']];

            }
        }

        //metodo para tranformar el dia del plan de vuelo a una fecha en base a la semana elegida por el usuario
        $this->mapDate($result, $week);

        //retorno la week para después mandárselo al método que crea el vuelo y así asiganrle el campo 'departure_week'

        return ['flight_plans' => $result, 'week' => $week_number];
    }

    //función que obtiene el id del tipo de vuelo automáticamente
    private function getTypeFlightByExistingStop($destination)
    {
        $existingTypes = $this->database->query("SELECT DISTINCT tf.id as type_id FROM stop s
                                        INNER JOIN flight f ON s.id_flight = f.id_flight
                                        INNER JOIN flight_plan fp ON f.id_flight_plan = fp.id
                                        INNER JOIN type_flight tf ON fp.type_flight = tf.id
                                        WHERE s.id_location = '$destination'");
        $types = [];

        //itero el array que me trajo como resultado en caso de que haya encontrado 2 tipos de vuelo
        //y lo retorno ya formateado por ejemplo [2,3]
        for ($i = 0; $i < sizeof($existingTypes); $i++) {
            $type = implode("','", $existingTypes[$i]);
            $types[] = $type;
        }

        return $types;
    }

    //función para crear el vuelo
    public function createFlight($id_flight_plan, $departure_date, $departure_time, $departure, $week)
    {
        $ship = $this->getAvailableShip($id_flight_plan);

        if (!$ship) {
            return 'Error';
        }

        $id_flight = $this->generateIdFlight(); //genero un entero random para el id del vuelo

        //creo fecha
        $datetime = date_create($departure_date . " " . $departure_time);
        $date = date_format($datetime, 'Y-m-d');
        $time = date_format($datetime, 'H:m:s');

        //consulto si ya existe el vuelo

        $createdFlight = $this->database->query("SELECT id_flight FROM flight WHERE id_flight_plan = '$id_flight_plan' AND departure_date = '$date' AND departure_hour = '$time'");

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

    private function consultTypeFlight($destination)
    {

        // cada array almacena el id del los destinos

        //orbital
        $type_flight_1 = ['Buenos Aires' => 1, 'Ankara' => 2];

        //circuito corto
        $type_flight_2 = ['EEI' => 3, 'Orbital Hotel' => 4, 'Luna' => 5, 'Marte' => 6];

        //circuito largo
        $type_flight_3 = ['EEI' => 3, 'Luna' => 5, 'Marte' => 6, 'Ganimedes' => 7, 'Europa' => 8, 'Io' => 9, 'Encedalo' => 10, 'Titan' => 11];

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

        echo "Tipo de vuelo buscado automáticamente:";
        echo "<br>";
        echo var_dump($type);
        echo "<br>";

        //pregunto si existe alguna difrencia (por ejemplo) entre el array type ([2,3]) y el array existingTypes en la
        //base de datos ([3]). Si hay diferencia

        $existingTypes = $this->getTypeFlightByExistingStop($destination);

        echo "Tipo de vuelo buscado en vuelos creados que comparten el mismo destino:";
        echo "<br>";
        echo var_dump($existingTypes);
        echo "<br>";

        if (array_diff($type, $existingTypes)) {
            $type = array_unique(array_merge($type, $existingTypes));
        }

        echo "Tipo de vuelo final:";
        echo "<br>";
        echo var_dump($type);
        echo "<br>";
        return $type;
    }
}