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
        $appointment = $this->appointmentModel->getAppointment($_SESSION["nickname"]);
        $data = ['appointment' => $appointment];

        if($appointment){
            return $this->printer->generateView('medicalcheckupView.html', $data);
        }

        $medicalCenters = $this->appointmentModel->getMedicalCenters();
        $data = ['medicalCenters' => $medicalCenters];

        return $this->printer->generateView('medicalcheckupFormView.html', $data);
    }

    public function getAppointment(){
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
            $data['medicalCenters'] = $this->appointmentModel->getMedicalCenters();
            return $this->printer->generateView('medicalcheckupFormView.html', $data);
        }

        $data = $this->appointmentModel->createAppointment($nickname, $date, $medicalCenter);

        if( isset($data['errors']) ){
            $data['medicalCenters'] = $this->appointmentModel->getMedicalCenters();
            return $this->printer->generateView('medicalcheckupFormView.html', $data);
        }

        return $this->printer->generateView('medicalcheckupSuccessView.html', $data);
    }

    public function deleteAppointment(){
        $data = $this->appointmentModel->getAppointment($_SESSION['nickname']);

        $this->appointmentModel->deleteAppointment($_SESSION['nickname']);

        return $this->printer->generateView('medicalcheckupDeleteView.html', $data);
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