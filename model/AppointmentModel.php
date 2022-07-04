<?php

class AppointmentModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function createAppointment($nickname, $date, $medicalCenter)
    {

        $errors = $this->modelValidation($nickname, $date, $medicalCenter);

        if (!$errors) {
            $this->database->query("
                INSERT INTO appointment (date, user_nickname, id_medical_center)
                VALUES ('$date','$nickname', '$medicalCenter')
            ");
            $this->createTraveler($nickname);
        }

        return $errors;
    }

    private function modelValidation($nickname, $date, $medicalCenter)
    {
        $errors = [];

        if ($this->getAppointment($nickname)) {
            $errors[] = 'Usted ya posee un turno asignado';
        }

        if (!$this->isRoomForAppointment($date, $medicalCenter)) {
            $errors[] = "El día $date no se encuentran turnos disponibles en el centro médico de $medicalCenter";
        }

        return $errors;
    }

    public function getAppointment($nickname)
    {
        $res = $this->database->query("
            SELECT ap.date, ap.user_nickname, mc.name as medicalCenter
            FROM appointment ap INNER JOIN medical_center mc ON ap.id_medical_center = mc.id
            WHERE user_nickname = '$nickname'
        ");
        return $res ? $res[0] : false;
    }

    private function isRoomForAppointment($date, $medicalCenter)
    {
        $appointmentsInDate = $this->getAppointmentsInDate($date, $medicalCenter);
        $limit = $this->getLimitAppointments($medicalCenter);

        $available = $limit - $appointmentsInDate;

        return $available > 0;
    }

    private function getAppointmentsInDate($date, $medicalCenter)
    {
        $data = $this->database->query("
            SELECT COUNT(*) as c
            FROM appointment ap INNER JOIN medical_center mc ON ap.id_medical_center = mc.id
            WHERE id_medical_center = '$medicalCenter' AND date = '$date'
        ");

        return (int)$data[0]["c"];
    }

    private function getLimitAppointments($medicalCenter)
    {
        $data = $this->database->query("
            SELECT daily_limit as c
            FROM medical_center
            WHERE id = '$medicalCenter'
        ");

        return (int)$data[0]["c"];
    }

    private function createTraveler($nickname)
    {
        $travelerCode = $this->generateTravelerCode();
        $flight_level = rand(1, 3);
        $this->database->query("
            UPDATE client
            SET traveler_code = '$travelerCode',
                flight_level = '$flight_level'
            WHERE user_nickname = '$nickname'
        ");
    }

    private function generateTravelerCode()
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($caracteres), 0, 10);
    }

    public function deleteAppointment($nickname)
    {
        $this->database->query("
            DELETE
            FROM appointment
            WHERE user_nickname = '$nickname'
        ");
    }

    public function getMedicalCenters()
    {
        return $this->database->query("
            SELECT id, name
            FROM medical_center
        ");
    }

    public function getNameMedicalCenter($id)
    {
        return $this->database->query("SELECT * FROM medical_center WHERE id=$id")[0]['name'];
    }

    public function getFlightLevel($nickname)
    {
        $result = $this->database->query("SELECT flight_level FROM client WHERE user_nickname = '$nickname'");
        return $result[0]['flight_level'];

    }
}