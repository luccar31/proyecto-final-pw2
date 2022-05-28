<?php

class MedicalcheckupController
{
    private $printer;
    private $appointmentModel;

    public function __construct($appointmentModel, $printer) {
        $this->appointmentModel = $appointmentModel;
        $this->printer = $printer;
    }

    public function execute(){
        $this->printer->generateView('medicalcheckupView.html');
    }

    public function getAppointment(){
        $date = new DateTime($_POST['date']);
        $nickname = $_SESSION['nickname'];
        $medicalCenter = $_POST['medicalCenter'];

        //todo: faltaría validar que hayan turnos disponibles ese día
        //Si no (!) es fecha valida, genera vista con mensaje de error y corta la ejecución
        if(!$this->validDate($date)){
            $this->printer->generateView('medicalcheckupView.html', ['error' => 'Ingrese una fecha correcta']);
            exit();
        }

        //Si el usuario ya tiene turno, genera vista con mensaje de error y corta la ejecución
        if($this->appointmentModel->getAppointment($nickname)){
            $this->printer->generateView('medicalcheckupView.html', ['error' => 'Usted ya posee un turno asignado']);
            exit();
        }
        else{ //Caso contrario, formateo fecha, llamo al modelo y genero vista con mensaje exitoso
            $date = $date->format('Y-m-d');
            $this->appointmentModel->createAppointment($nickname, $date, $medicalCenter);
            $this->printer->generateView('medicalcheckupView.html', ['exito' => 'Se ha reservado su turno exitosamente']);
        }
    }

    /*
     * Función que valida si la fecha ingresada es mayor o igual al dia de la fecha.
     * No se pueden reservar turnos para fechas que ya pasaron
     */
    private function validDate($input){
        $now = new DateTime();
        /*
         * Diferencia de fechas: hoy - fecha ingresada
         * https://www.php.net/manual/en/class.datetime.php
         * (int) -> Casteo explícito al entero
         */
        $diff = (int)$now->diff($input)->format('%r%a');
        return $diff >= 0;
    }
}