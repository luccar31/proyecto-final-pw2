<?php

class AppointmentModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getAppointment($nickname){
        return $this->database->query("SELECT ap.date, ap.user_nickname, mc.name as medicalCenter
        FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
        WHERE user_nickname = '$nickname'");
    }

    public function createAppointment($nickname, $date, $medicalCenter){
        $data = [];

        if($this->getAppointment($nickname)){
            $data['errors'][] = ['error' => 'Usted ya posee un turno asignado'];
        }

        if(!$this->isRoomForAppointment($date, $medicalCenter)){
            $data['errors'][] = ['error' => "El día {$date->format('d-m-Y')} no se encuentran turnos disponibles en el centro médico seleccionado"];
        }

        if(isset($data['errors'])) return $data;

        $this->database->query("INSERT INTO appointment (date, user_nickname, medical_center_id) VALUES ('{$date->format('Y-m-d')}','$nickname', '$medicalCenter')");

        return ['nickname' => $nickname, 'date' => $date->format('d-m-Y'), 'medicalCenter' => $medicalCenter];
    }

    public function modifyAppointment($nickname, $date){
        $this->database->query("UPDATE appointment SET date = '$date' WHERE user_nickname = '$nickname'");
    }

    public function deleteAppointment($nickname){
        $this->database->query("DELETE FROM appointment WHERE user_nickname = '$nickname'");
    }

    private function getAppointmentsInDate($date, $medicalCenter){
        $data = $this->database->query("SELECT COUNT(*) as c
        FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
        WHERE medical_center_id = '$medicalCenter' AND date = '$date'");

        return (int)$data[0]["c"];
    }

    private function getLimitAppointments($medicalCenter){
        $data = $this->database->query("SELECT daily_limit as c
        FROM medical_center
        WHERE id = '$medicalCenter'");

        return (int)$data[0]["c"];
    }

    private function isRoomForAppointment($date, $medicalCenter){
        $appointmentsInDate = $this->getAppointmentsInDate($date->format('Y-m-d'), $medicalCenter);
        $limit = $this->getLimitAppointments($medicalCenter);

        $available = $limit - $appointmentsInDate;

        return $available > 0;
    }
}