<?php

class TicketModel
{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function search($typeCabin = null, $typeService = null){

        if ($typeCabin) {

        }

    }

    public function createTicket($id_flight, $id_type_cabin, $id_service, $userNickname, $num_tickets){
        for ($i = 0; $i < $num_tickets; $i++) {
            $this->database->query("INSERT INTO ticket (id_flight, id_cabin, id_service, user_nickname)
                                    VALUES ('$id_flight', '$id_type_cabin', '$id_service', '$userNickname')");
        }
    }

    public function findClientTickets($nickname){
        $resu = $this->database->query("SELECT f.departure_date, f.departure_hour, l.name as departure , tc.description as type_cabin, s.description as service
                                        FROM ticket t
                                        INNER JOIN cabin c ON t.id_cabin = c.id
                                        INNER JOIN type_cabin tc ON c.id_type = tc.id
                                        INNER JOIN flight f ON t.id_flight = f.id_flight
                                        INNER JOIN flight_plan fp ON f.id_flight_plan = fp.id
                                        INNER JOIN location l ON fp.departure_loc = l.id
                                        INNER JOIN service s ON t.id_service = s.id 
                                        WHERE user_nickname = '$nickname'");
        return ['tickets' => $resu];
    }


    public function validateCapacityCabin($id_flight, $id_type_cabin, $num_tickets){
        //trae la capacidad de la cabina elegida
        $capacityCabinData = $this->database->query("SELECT c.capacity
                                            FROM flight f
                                            INNER JOIN ship s ON f.id_ship = s.id
                                            INNER JOIN equipment_cabin ec on s.id_equipment = ec.id_equipment
                                            INNER JOIN cabin c on ec.id_cabin = c.id
                                            INNER JOIN type_cabin tc on c.id_type = tc.id
                                            WHERE f.id_flight = '$id_flight'
                                            AND tc.id = '$id_type_cabin'");

        //trae el total de tickets para la cabina elegida en el vuelo seleccionado
        $countFlightTicketsData = $this->database->query("SELECT count(id) as num_tickets
                                                     FROM ticket
                                                     WHERE id_flight = '$id_flight'
                                                     AND id_cabin = '$id_type_cabin'");


        $countFlightTickets = $countFlightTicketsData[0]["num_tickets"];
        $capacityCabin = $capacityCabinData[0]["capacity"];

        if($num_tickets < 0){
            $data['invalidInput'] = "No puede ingresar valores negativos";
            $data['isValid'] = false;
            return $data;
        }

        if($countFlightTickets + $num_tickets <= $capacityCabin){
            $data['isValid'] = true;
            return $data;
        }else{
            $data['capacityCabin'] = "Capacidad maxima de esta cabina: $capacityCabin";
            $data['countFlightTickets'] = "Capacidad actual de esta cabina: $countFlightTickets";
            $data['outOfCapacityError'] = "Se alcanzo la capacidad maxima permitida de pasajeros para este tipo de cabina, por favor elija otra";
            $data['isValid'] = false;
        }

        return $data;

    }

    public function validDate($id_flight){
        $dateFlightData = $this->database->query("SELECT f.departure_date
                                                 FROM flight f
                                                 WHERE f.id_flight = '$id_flight'");

        $typeFlightData = $this->database->query("SELECT tf.description,tf.id as type_flight,te.description, te.id  as type_equip
                                                  FROM flight f
                                                  INNER JOIN flight_plan fp ON f.id_flight_plan = fp.id
                                                  INNER JOIN type_flight tf on fp.type_flight = tf.id
                                                  INNER JOIN equipment e on fp.id_equipment = e.id
                                                  INNER JOIN type_equipment te on e.id_type = te.id
                                                  WHERE f.id_flight = '$id_flight'");


        // a la fecha de salida del vuelo se le suman las horas que hay hasta departure,
        // y se lo compara con el dia actual para saber si hay una diferencia de mas de 24 hs

        $currentDate_ = new DateTime();
        $currentDate = $currentDate_->format('Y-m-d');
        $dateFlight = $dateFlightData[0]['departure_date'];
        $typeFlight = $typeFlightData[0]['type_flight'];
        $typeEquip = $typeFlightData[0]['type_equip'];
        $departure = 5;          //$_SESSION['depart'];


//        echo "fecha vuelo partida: " . $dateFlight;
//        echo "tipo vuelo partida: " . $typeFlight;
//        echo "equipo vuelo partida: " . $typeEquip;
//        echo "origen vuelo partida: " . $departure . "</br>";


        $routeDateData = $this->database->query("SELECT rl.id_location,l.name as loc, rl.diff_time, rl.order_
                                             FROM route r
                                             INNER JOIN route_location rl on r.id = rl.id_route
                                             INNER JOIN location l on rl.id_location = l.id
                                             WHERE id_type = '$typeFlight'
                                             AND id_type_equipment = '$typeEquip'");


        //var_dump($routeDateData);


        switch ($departure){
            case 5:
                $i = 0;
                do{
                    $diff_time = $routeDateData[$i]['diff_time'];
                    $newDate =+ strtotime("+{$diff_time} hour", strtotime(currentDate));
                    var_dump($newDate);
                    $i++;
                }while($routeDateData[0][id_location] != $departure);
            break;

        }







    }

}