<?php

class AppointmentModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getAppointment($nickname){
        return $this->database->query("SELECT ap.date, ap.user_nickname, mc.name
        FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
        WHERE user_nickname = '$nickname'");
    }

    public function createAppointment($nickname, $date, $medicalCenter){

        $travelerCode = $this->generateTravelerCode();
        $flight_level = rand(1, 3);

        $this->database->query("INSERT INTO appointment (date, user_nickname, medical_center_id) VALUES ('$date','$nickname', '$medicalCenter')");
        $this->database->query("UPDATE client set traveler_code = '$travelerCode', flight_level = '$flight_level' WHERE user_nickname = '$nickname'");
    }

    public function modifyAppointment($nickname, $date){
        $this->database->query("UPDATE appointment SET date = '$date' WHERE user_nickname = '$nickname'");
    }

    public function deleteAppointment($nickname){
        $this->database->query("DELETE FROM appointment WHERE user_nickname = '$nickname'");
    }

    // función para generar codigo de viajero
    private function generateTravelerCode(){
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        do{
            //creo el codigo random y consulto en la bd si existe, las posibilidades son millones pero por las dudas
            $randomCode = substr(str_shuffle($caracteres), 0, 10);
            $result = $this->database->query("SELECT * FROM client WHERE traveler_code = '$randomCode'");
        }
        while (!empty($result));

        return $randomCode;
    }
}