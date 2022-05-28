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
        $this->database->query("INSERT INTO appointment (date, user_nickname, medical_center_id) VALUES ('$date','$nickname', '$medicalCenter')");
    }

    public function modifyAppointment($nickname, $date){
        $this->database->query("UPDATE appointment SET date = '$date' WHERE user_nickname = '$nickname'");
    }

    public function deleteAppointment($nickname){
        $this->database->query("DELETE FROM appointment WHERE user_nickname = '$nickname'");
    }
}