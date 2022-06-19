<?php

class Flight_planModel{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPlansOrFlight($type, $departure, $week, $destination){

        $result = $this->database->query("SELECT fp.id as id, e.model as model, fp.departure_day as day, l.name as departure, tf.description as type, fp.departure_time as time FROM flight_plan fp
                                           INNER JOIN equipment e on fp.id_equipment = e.id
                                           INNER JOIN days d on fp.departure_day = d.id
                                           INNER JOIN location l on fp.departure_loc = l.id
                                           INNER JOIN type_flight tf on fp.type_flight = tf.id
                                           WHERE tf.id = '$type' AND l.id = '$departure'");

        $result2 = $this->database->query("SELECT * FROM flight");

        $this->mapDate($result, $week);

        return  ['flight_plans' => $result, 'flight' => $result2];
    }

    private function getRoute($id_flight_plan){
        $plan = $this->getPlan($id_flight_plan);
        $equipment = $this->getEquipment($plan["id_equipment"]);

        $route = $this->database->query("SELECT * FROM route WHERE id_type_equipment = '$equipment[id_type]' AND id_type_flight = '$plan[type_flight]'");

        return $route[0];
    }

    private function getJourney($route_id, $order, $origin){
        return $this->database->query("SELECT * FROM journey WHERE id_route = '$route_id' AND id_location <> '$origin' ORDER BY order_ $order");
    }

    private function createStops($id_flight_plan, $id_flight, $departure_date, $departure_time, $name_origin, $order){

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
        foreach ($journey as $stop){

            date_modify($d,"+" . $stop['diff_time'] . "hours");

            $time = date_format($d, 'H:i:s');
            $date = date_format($d, 'Y-m-d');

            $this->database->query("INSERT INTO stop (id_flight, id_location, arrive_time, arrive_date)
                                    VALUES ('$id_flight','$stop[id_location]','$time','$date')
                                   ");
        }

    }

    public function createFlight($id_flight_plan, $departure_date, $departure_time, $departure){
        $ship = $this->getAvailableShip($id_flight_plan);

        if(!$ship){
            return 'Error';
        }

        $id_flight = $this->generateIdFlight(); //genero un entero random para el id del vuelo
        //creo fecha

        $datetime = date_create($departure_date . " " . $departure_time);
        $date = date_format($datetime, 'Y-m-d');
        $time = date_format($datetime, 'H:m:s');
        //creo el vuelo
        $this->database->query("INSERT INTO flight (id_flight ,id_flight_plan, id_ship, departure_date, departure_hour)
                                VALUES ('$id_flight','$id_flight_plan','$ship[id]','$date','$time')
                                ");

        $this->createStops($id_flight_plan, $id_flight, $departure_date, $departure_time, $departure, 'asc'); //creo sus escalas en el orden comun

        return 0;
    }

    private function getAvailableShip($id_flight_plan){
        $plan = $this->getPlan($id_flight_plan);
        $ship = $this->database->query("SELECT * FROM ship WHERE id_equipment=$plan[id_equipment] AND available = true");
        return $ship[0];
    }

    private function getPlan($id_flight_plan){
        $plan = $this->database->query("SELECT * FROM flight_plan WHERE id='$id_flight_plan'");
        return $plan[0];
    }

    public function getTypeFlights(){
        $types = $this->database->query("SELECT * FROM type_flight");
        return $types;
    }

    public function getCities($type_flight){
        $res = $this->database->query("SELECT id, name from location
                                       WHERE id IN(
                                                   SELECT id_location FROM journey j
                                                   WHERE id_route IN (
                                                                      SELECT r.id FROM route r
                                                                      WHERE r.id_type_flight = '$type_flight'
                                                                      GROUP BY r.id_type_flight
                                                                     )
                                                  )
                                      ");
        if($type_flight != 1){
            $res2 = $this->database->query("SELECT l.id, name FROM location l
                                        INNER JOIN flight_plan fp ON fp.departure_loc = l.id
                                        GROUP BY fp.departure_loc;
                                       ");
            foreach ($res2 as $r){
                $res[] = $r;
            }
        }

        return $res;
    }

    private function mapDate(&$array, $week){

        $res = $this->obtainValuesFromWeekStr($week);
        $year = (int)$res['year'];
        $week_no = (int)$res['weekno'];

        $date = new DateTime();

        foreach($array as &$register){
            $register['day'] = $date->setISODate($year, $week_no, (int)$register['day'] + 1)->format('d-m-Y');
        }
    }

    private function obtainValuesFromWeekStr($str){
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
}