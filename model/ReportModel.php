<?php

class ReportModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function billingPerClient(){
        return $this->database->query("SELECT user_nickname, SUM(totalPrice) as suma FROM payment GROUP BY user_nickname");

    }

    public function monthlyBilling($year = null){
        $year = $year ? $year : date("Y");

        $billing = $this->database->query("SELECT MONTH(date) as Mes, SUM(totalPrice) as Total FROM payment 
                                            WHERE YEAR(date) = '$year'
                                            GROUP BY MONTH(date)
                                            ORDER BY MONTH(date)");


        $data = array_fill(1,12,0);

        foreach($billing as $register){
             $data[(int)$register['Mes']] = (int)$register['Total'];
        }

        return array_values($data);
    }

    public function mostSoldCabin(){
        $cabin = $this->database->query("SELECT tc.description, COUNT(tc.id) as Cantidad FROM ticket
                                INNER JOIN cabin c on ticket.id_cabin = c.id
                                INNER JOIN type_cabin tc on c.id_type = tc.id
                                GROUP BY tc.id");

        $cabin = array_column($cabin, 'Cantidad');
        return $cabin;
    }
}