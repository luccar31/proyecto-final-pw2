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
        $nickname = $_SESSION["nickname"];
        $appointment = $this->appointmentModel->getAppointment($nickname);
        $data = ['appointment' => $appointment];

        $this->printer->generateView('medicalcheckupView.html', $data);
    }

    public function getAppointment(){
        $data = [];
        $nickname = $_SESSION['nickname'];
        $date = new DateTime($_POST['date']);
        $medicalCenter = $_POST['medicalCenter'];

        if( !$this->validDate($date) ){
            $data['errors'][] = ['error' => 'Ingrese una fecha correcta'];
        }

        if( !$this->validMedicalCenter($medicalCenter) ){
            $data['errors'][] = ['error' => 'Ingrese un centro medico'];
        }

        if( isset($data['errors']) ){
            return $this->printer->generateView('medicalcheckupView.html', $data);
        }

        $data = $this->appointmentModel->createAppointment($nickname, $date, $medicalCenter);

        if( isset($data['errors']) ){
            return $this->printer->generateView('medicalcheckupView.html', $data);
        }

        return $this->printer->generateView('medicalcheckupSuccessView.html', $data);
    }

    private function validDate($input){
        $now = new DateTime();
        $diff = (int)$now->diff($input)->format('%r%a');
        return $diff >= 0;
    }

    private function validMedicalCenter($input){
        return $input != 0;
    }
}