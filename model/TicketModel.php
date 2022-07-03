<?php

class TicketModel
{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    /*public function search($typeCabin = null, $typeService = null){

        if ($typeCabin) {

        }

    }*/

    public function createTicket($id_flight, $type_cabin, $id_service, $userNickname, $num_tickets, $departure, $destination){

        $cabin = $_SESSION['cabin'];

        for ($i = 0; $i < $num_tickets; $i++) {
            $this->database->query("INSERT INTO ticket (id_flight, id_cabin, id_service, user_nickname, departure, destination)
                                    VALUES ('$id_flight', '$cabin', '$id_service', '$userNickname', '$departure', '$destination')");
        }
    }

    public function findClientTickets($nickname){
        return $this->database->query("SELECT t.id, f.departure_date, f.departure_hour, l.name as departure , l2.name as destination, 
                                        tc.description as type_cabin, s.description as service, f.id_ship, fp.type_flight as id_type_flight
                                        FROM ticket t
                                        INNER JOIN cabin c ON t.id_cabin = c.id
                                        INNER JOIN type_cabin tc ON c.id_type = tc.id
                                        INNER JOIN flight f ON t.id_flight = f.id_flight
                                        INNER JOIN flight_plan fp ON f.id_flight_plan = fp.id
                                        INNER JOIN location l ON fp.departure_loc = l.id
                                        INNER JOIN location l2 ON t.destination = l2.id
                                        INNER JOIN service s ON t.id_service = s.id 
                                        WHERE user_nickname = '$nickname'");
    }

    public function getCabins($id_flight_plan){
        return $this->database->query("SELECT DISTINCT t.* FROM type_cabin t 
                                       JOIN cabin c ON t.id = c.id_type
                                       WHERE c.id IN(SELECT c.id FROM cabin c
                                                     JOIN equipment_cabin ec on c.id = ec.id_cabin
                                                     JOIN flight_plan fp ON ec.id_equipment = fp.id_equipment
                                                     WHERE fp.id = '$id_flight_plan')
                                      ");
    }

    public function getServices($id_flight_plan){
        return $this->database->query("SELECT * FROM service GROUP BY price desc");
    }



    public function findClientTicket($id_flight, $nickname, $type_cabin){
        return $this->database->query("SELECT DISTINCT f.departure_date, f.departure_hour, l.name as departure , l2.name as destination, 
                                        tc.description as type_cabin, s.description as service, f.id_ship, fp.type_flight as id_type_flight
                                        FROM ticket t
                                        INNER JOIN cabin c ON t.id_cabin = c.id
                                        INNER JOIN type_cabin tc ON c.id_type = tc.id
                                        INNER JOIN flight f ON t.id_flight = f.id_flight
                                        INNER JOIN flight_plan fp ON f.id_flight_plan = fp.id
                                        INNER JOIN location l ON fp.departure_loc = l.id
                                        INNER JOIN location l2 ON t.destination = l2.id
                                        INNER JOIN service s ON t.id_service = s.id 
                                        WHERE user_nickname = '$nickname' AND t.id_flight = '$id_flight' AND tc.id = '$type_cabin'");
    }

    public function validateCapacityCabin($id_flight_plan, $type_cabin, $num_tickets){

        //trae la capacidad de la cabina elegida
        $capacityCabinData = $this->database->query("SELECT c.capacity
                                            FROM flight_plan fp
                                            INNER JOIN equipment e ON fp.id_equipment = e.id
                                            INNER JOIN equipment_cabin ec on e.id = ec.id_equipment
                                            INNER JOIN cabin c on ec.id_cabin = c.id
                                            INNER JOIN type_cabin tc on c.id_type = tc.id
                                            WHERE fp.id = '$id_flight_plan'
                                            AND tc.id = '$type_cabin'");


        $cabin = $this->getCabinByTypeAndFlight($id_flight_plan, $type_cabin);


        //trae el total de tickets para la cabina elegida en el vuelo seleccionado
        $countFlightTicketsData = $this->database->query("SELECT count(t.id) as num_tickets
                                                          FROM ticket t
                                                          WHERE t.id_cabin = '$cabin'");


        $countFlightTickets = $countFlightTicketsData[0]["num_tickets"];
        $capacityCabin = $capacityCabinData[0]["capacity"];
        $availables = $capacityCabin-$countFlightTickets;

        if($countFlightTickets + $num_tickets <= $capacityCabin){
            $data['isValid'] = true;
        }else{
            $data['capacityCabin'] = "Capacidad: $capacityCabin asientos.";
            $data['countFlightTickets'] = "Disponibles: $availables";
            $data['outOfCapacityError'] = "Se alcanzo la capacidad maxima permitida de pasajeros para este tipo de cabina, por favor elija otra";
            $data['isValid'] = false;
        }
        return $data;

    }

    private function getCabinByTypeAndFlight($id_flight_plan, $id_type_cabin){

        $idCabin = $this->database->query("SELECT c.id from cabin c 
                                           where c.id_type = '$id_type_cabin' AND c.id IN(SELECT ec.id_cabin FROM equipment_cabin ec 
                                                                           JOIN flight_plan fp on ec.id_equipment = fp.id_equipment WHERE fp.id = '$id_flight_plan')");

        $_SESSION['cabin'] = $idCabin[0]['id'];

        return $_SESSION['cabin'];
}

}