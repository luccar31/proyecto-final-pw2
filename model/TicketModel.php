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

    public function createTicket($id_flight, $id_type_cabin, $id_service, $userNickname, $num_tickets, $departure, $destination){
        echo var_dump($id_flight);
        echo "<br>";
        echo var_dump($id_type_cabin);
        echo "<br>";
        echo var_dump($id_service);
        echo "<br>";
        echo var_dump($userNickname);
        echo "<br>";
        echo var_dump($num_tickets);
        echo "<br>";
        for ($i = 0; $i < $num_tickets; $i++) {
            $this->database->query("INSERT INTO ticket (id_flight, id_cabin, id_service, user_nickname, departure, destination)
                                    VALUES ('$id_flight', '$id_type_cabin', '$id_service', '$userNickname', '$departure', '$destination')");
        }
    }

    public function findClientTickets($nickname){
        $resu = $this->database->query("SELECT f.departure_date, f.departure_hour, l.name as departure , l2.name as destination, 
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
        return ['tickets' => $resu];
    }

    public function getCabins($id_flight_plan){
        return $this->database->query("SELECT * FROM type_cabin GROUP BY price desc");
    }

    public function getServices($id_flight_plan){
        return $this->database->query("SELECT * FROM service GROUP BY price desc");
    }

    public function calculatePrice($id_flight_plan, $num_tickets, $id_service, $id_type_cabin)
    {

        $departure = $_SESSION['departure'];
        $destination = $_SESSION['destination'];
        $segmentPrice = 1000;

        $priceCabin = $this->database->query("SELECT price FROM type_cabin WHERE id = '$id_type_cabin'");
        $priceService = $this->database->query("SELECT price FROM service WHERE id = '$id_service'");
        $segments = $this->database->query("SELECT (j2.order_ - j1.order_) resta FROM flight_plan fp

                                            INNER JOIN equipment e on fp.id_equipment = e.id
                                            INNER JOIN type_equipment te on e.id_type = te.id
                                            INNER JOIN route r1 on te.id = r1.id_type_equipment
                                            INNER JOIN journey j1 on r1.id = j1.id_route
                                            INNER JOIN journey j2 on r1.id = j2.id_route
                                            
                                            WHERE fp.id = '$id_flight_plan' AND r1.id_type_flight = fp.type_flight AND j1.id_location = '$departure' AND j2.id_location = '$destination'");


        //si llega a ser un vuelo ankara - buenos aires no hay tramo real, al ser ambos posición 0 la resta da 0, por lo tanto le seteamos el tramo en 1
        if ($segments[0]['resta'] == 0){
            $segments[0]['resta'] = 1;
        }

        $totalPrice = (($priceCabin[0]['price']+$priceService[0]['price'] + ($segments[0]['resta'] * $segmentPrice))   * $num_tickets);
       return ['totalPrice' => $totalPrice, 'priceCabin' => $priceCabin[0]['price'], 'priceService' => $priceService[0]['price'],
           'segments' => $segments[0]['resta'], 'num_tickets' => $num_tickets, 'segmentPrice' => $segmentPrice];

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

        if($countFlightTickets + $num_tickets <= $capacityCabin){
            $data['isValid'] = true;
        }else{
            $data['capacityCabin'] = "Capacidad maxima de esta cabina: $capacityCabin";
            $data['countFlightTickets'] = "Capacidad actual de esta cabina: $countFlightTickets";
            $data['outOfCapacityError'] = "Se alcanzo la capacidad maxima permitida de pasajeros para este tipo de cabina, por favor elija otra";
            $data['isValid'] = false;
        }

        return $data;

    }

    /*public function validate($id_flight){
        $dateFlightData = this->database->query("SELECT f.departure_date
                                                 FROM flight f
                                                 WHERE f.id_flight = '$id_flight'");

        $dateFlight = $dateFlightData[0][departure_date];


    }*/

}