<?php

class ReportModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function billingPerClient(){
        return $this->database->query("SELECT user_nickname, SUM(totalPrice) FROM payment GROUP BY user_nickname");
    }

    public function monthlyBilling($year = null){
        $year = $year ? $year : date("Y");

        $billing = $this->database->query("SELECT MONTH(date) as Mes, SUM(totalPrice) as Total FROM payment 
                                            WHERE YEAR(date) = '$year'
                                            GROUP BY MONTH(date)
                                            ORDER BY MONTH(date)");

        $months = array_map(function($elem){return date('M', mktime(0, 0, 0, (int)$elem, 10));},array_column($billing,'Mes'));
        $totals = array_values(array_column($billing,'Total'));

        return ['months' => $months, 'totals' => $totals];
    }

    public function mostSoldCabin(){
        $this->database->query("SELECT tc.description, COUNT(tc.id) as Cantidad FROM ticket
                                INNER JOIN cabin c on ticket.id_cabin = c.id
                                INNER JOIN type_cabin tc on c.id_type = tc.id
                                GROUP BY tc.id");
    }
}