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
        $ap = $this->appointmentModel->getAppointment($_SESSION["nickname"]);

        if(!$ap){
            Helper::redirect("/medicalcheckup/makeAppointmentForm");
            //return $this->printer->generateView('medicalcheckupView.html', $data);
        }

        Helper::redirect("/medicalcheckup/showAppointment?med={$ap['medicalCenter']}&d={$ap['date']}");
    }

    public function showAppointment(){
        $data['medicalCenter'] = $_GET['med'];
        $data['date'] = $_GET['d'];

        $this->printer->generateView('medicalcheckupView.html', $data);
    }

    public function makeAppointmentForm(){

        $data['medicalCenters'] = $this->appointmentModel->getMedicalCenters();
        $data['medicalCenter'] = isset($_GET['med']) ? $_GET['med'] : null;
        $data['date'] = isset($_GET['d']) ? $_GET['d'] : null;
        $data['errors'] = isset($_SESSION['errors']) ? $_SESSION['errors'] : null;
        unset($_SESSION['errors']);

        $this->printer->generateView('medicalcheckupFormView.html', $data);
    }

    public function makeAppointment(){
        $nickname = $_SESSION['nickname'];
        $date = $_POST['date'];
        $medicalCenter = $_POST['medicalCenter'];

        $errors = $this->formValidation($date, $medicalCenter);

        if( $errors ){
            $_SESSION['errors'] = $errors;
            Helper::redirect("/medicalcheckup/makeAppointmentForm?med={$medicalCenter}&d={$date}");
        }

        $response = $this->appointmentModel->createAppointment($nickname, $date, $medicalCenter);

        if( $response ){
            $_SESSION['errors'] = $response;
            Helper::redirect("/medicalcheckup/makeAppointmentForm?med={$medicalCenter}&d={$date}");
        }

        Helper::redirect("/medicalcheckup/successfullAppointment?med={$medicalCenter}&d={$date}");
    }

    public function successfullAppointment(){
        $data['medicalCenter'] = $_GET['med'];
        $data['date'] = $_GET['d'];

        $this->printer->generateView('medicalcheckupSuccessView.html', $data);
    }

    public function deleteAppointment(){
        $res = $this->appointmentModel->getAppointment($_SESSION['nickname']);
        $this->appointmentModel->deleteAppointment($_SESSION['nickname']);
        Helper::redirect("/medicalcheckup/appointmentDeleted?med={$res['medicalCenter']}&d={$res['date']}");
    }

    public function appointmentDeleted(){
        $data['medicalCenter'] = $_GET['med'];
        $data['date'] = $_GET['d'];

        $this->printer->generateView('medicalcheckupDeleteView.html', $data);
    }

    private function formValidation($date, $medicalCenter){
        $error = [];

        if( !$this->isValidDate($date) ){
            $error[] = 'Ingrese una fecha correcta';
        }

        if( !$this->isValidMedicalCenter($medicalCenter) ){
            $error[] = 'Ingrese un centro medico';
        }

        return $error;
    }

    private function isValidDate($input){
        $input = new DateTime($input);
        $now = new DateTime();
        $diff = (int)$now->diff($input)->format('%r%a');
        return $diff >= 0;
    }

    private function isValidMedicalCenter($input){
        return $input != 0;
    }

    private function mapErrors($arr){
        $res = [];
        foreach ($arr as $valor){
            $res[] = ['error' => $valor];
        }
        return $res;
    }
}