<?php

class CreditModel
{
    private $database;
    private $segmentPrice = 100;
    private $creditPrice = 10;
    private $medical = 100;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function calculatePrice($id_flight_plan, $num_tickets, $id_service, $id_type_cabin)
    {

        //consultar si ya pagó
        if (isset($_SESSION['nickname'])) {
            $this->consultMedicalPrice($_SESSION['nickname']);
        }


        $departure = $_SESSION['departure'];
        $destination = $_SESSION['destination'];


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

        //si no está vacio
        if (empty($segments)) {

            $segments = 1;
        } elseif ($segments[0]['resta'] == 0) {
            $segments = 1;
        } else {

            $segments = $segments[0]['resta'];
        }

        $totalPrice = ((((($priceCabin[0]['price'] + $priceService[0]['price']) + ($this->segmentPrice * $segments))) * $num_tickets + $this->medical) * $this->creditPrice);
        $totalPriceInCredit = ((((($priceCabin[0]['price'] + $priceService[0]['price']) + ($this->segmentPrice * $segments))) * $num_tickets + $this->medical));


        return ['totalPrice' => $totalPrice, 'priceCabin' => $priceCabin[0]['price'], 'priceService' => $priceService[0]['price'],
            'segments' => $segments, 'num_tickets' => $num_tickets, 'segmentPrice' => $this->segmentPrice, 'totalPriceInCredit' => $totalPriceInCredit, 'medical' => $this->medical];

    }

    private function consultMedicalPrice($nickname)
    {
        $user_nickname = $nickname;

        $result = $this->database->query("SELECT * from ticket WHERE user_nickname = '$user_nickname'");

        if (!empty($result)) {
            $this->medical = 0;
        }
    }

    public function registerPayment($titular, $nroTarjeta, $totalPrice, $nickname)
    {

        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:s');

        $this->database->query("INSERT INTO payment (titular, nroTarjeta, totalPrice, user_nickname, date)
                                VALUES ('$titular','$nroTarjeta','$totalPrice', '$nickname', '$date')");
    }

}