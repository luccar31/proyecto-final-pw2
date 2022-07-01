<?php

class CreditModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
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


        //como ankara-buenos aires son posicion 0, va a dar 0. Por lo tanto lo seteamos en 1.

        echo var_dump($segments);
        //si no está vacio
        if (empty($segments)) {

            $segments = 1;
        }
        elseif($segments[0]['resta'] == 0){
                $segments = 1;
            }
            else{

                $segments = $segments[0]['resta'];
            }

        $totalPrice = ( ( ($priceCabin[0]['price'] + $priceService[0]['price'] + $segmentPrice) * $segments) * $num_tickets);
        return ['totalPrice' => $totalPrice, 'priceCabin' => $priceCabin[0]['price'], 'priceService' => $priceService[0]['price'],
            'segments' => $segments, 'num_tickets' => $num_tickets, 'segmentPrice' => $segmentPrice];

    }

    public function validateTotalPrice($totalPrice){

        if ($_SESSION['totalPrice'] == $totalPrice){
            return true;
        }
        else{
            return false;
        }
    }
}