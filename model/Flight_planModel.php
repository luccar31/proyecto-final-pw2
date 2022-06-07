<?php

class Flight_planModel{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getAllFlight_plans(){
        $result =  $this->database->query("SELECT fp.id, tf.description, fp.departure_day, fp.departure_time, fp.departure, fp.destination, fp.ship_model
                                            FROM flight_plan fp
                                            INNER JOIN type_flight tf ON fp.id_type = tf.id");
        return  ['flight_plans' => $result];
    }

    public function search($typeFlight){

        if($typeFlight){
            $result =  $this->database->query("SELECT fp.id, tf.description, fp.departure_day, fp.departure_time, fp.departure, fp.destination, fp.ship_model
                                                FROM flight_plan fp
                                                INNER JOIN type_flight tf ON fp.id_type = tf.id
                                                WHERE id_type = '$typeFlight'");
            return  ['flight_plans' => $result];
        }

    }

}