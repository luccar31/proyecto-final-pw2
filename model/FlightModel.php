<?php

class FlightModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getFlights($origin, $destination, $flightType)
    {

        //si el origen o destino estan vacios que busque por el tipo
        if (empty($origin) || empty($destination)) {
            $result = $this->database->query("SELECT * FROM flight WHERE id_type = '$flightType'");
        }

        //sino, busca el origen y destino si es que existen
        else {
            $result = $this->database->query("SELECT * FROM flight WHERE destination = '$destination' AND origin = '$origin' AND id_type = '$flightType'");
        }

        //si no encontró nada, devuelve el array con el error
        if (empty($result)) {
            return ['error' => 'No hay vuelos disponibles'];
        }

        //si encontró algo, devuelve el array de los vuelos
        else {
            return ['flights' => $result];
        }


    }

    public function getAllFlights(){
        $result =  $this->database->query("SELECT f.id, tf.description, f.departure_date, f.departure_time, f.departure, f.destination, f.ship_model 
                                            FROM flight f
                                            INNER JOIN type_flight tf ON f.id_type = tf.id");
        return  ['flights' => $result];
    }

    public function search($typeFlight){

        if($typeFlight){
            $result =  $this->database->query("SELECT f.id, tf.description, f.departure_date, f.departure_time, f.departure, f.destination, f.ship_model
                                                FROM flight f 
                                                INNER JOIN type_flight tf ON f.id_type = tf.id
                                                WHERE id_type = '$typeFlight'");
            return  ['flights' => $result];
        }

    }

}