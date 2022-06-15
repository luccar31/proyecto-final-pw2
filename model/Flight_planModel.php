<?php

class Flight_planModel{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getAllFlight_plans($type, $departure, $destination, $week){
        $result =  $this->database->query("SELECT fp.id, tf.description, fp.departure_day, fp.departure_time,l1.name as departure,  l1.name as destination, fp.ship_model
                                           FROM flight_plan fp
                                           INNER JOIN type_flight tf ON fp.id_type = tf.id
                                           INNER JOIN location l1 ON fp.departure = l1.id
                                           INNER JOIN location l2 ON fp.destination = l2.id
                                           WHERE id_type = $type AND
                                                 departure = $departure");

        $this->mapDate($result, $week);

        return  ['flight_plans' => $result];
    }

    public function search($typeFlight){
        $result = $this->database->query("SELECT fp.id, tf.description, fp.departure_day, fp.departure_time, fp.departure, fp.destination, fp.ship_model
                                           FROM flight_plan fp
                                           INNER JOIN type_flight tf ON fp.id_type = tf.id
                                           WHERE id_type = '$typeFlight'");
            return  ['flight_plans' => $result];
    }

    public function searchForId($id_flight_plan){
        $result =  $this->database->query("SELECT fp.id, tf.description, fp.departure_day, fp.departure_time, fp.departure, fp.destination, fp.ship_model
                                                FROM flight_plan fp
                                                INNER JOIN type_flight tf ON fp.id_type = tf.id
                                                WHERE fp.id = '$id_flight_plan'");
        return  ['flight_plans' => $result];

    }

    public function getFlights($typeFlight, $departure, $destination, $week){
        return [];
    }

    public function getTypeFlights(){
        return $this->database->query("SELECT * FROM type_flight");
    }

    public function getCities(){
        return $this->database->query("SELECT * FROM location");
    }

    private function mapDate(&$array, $week){

        $res = $this->obtainValuesFromWeekStr($week);
        $year = (int)$res['year'];
        $week_no = (int)$res['weekno'];

        $date = new DateTime();

        foreach($array as &$register){
            $register['departure_day'] = $date->setISODate($year, $week_no, (int)$register['departure_day'] + 1)->format('d-m-Y');
        }
    }

    private function obtainValuesFromWeekStr($str){
        $matches = [];
        preg_match('/(?<year>\d{4})-W(?<weekno>\d{1,2})/', $str, $matches);
        return $matches;
    }

}