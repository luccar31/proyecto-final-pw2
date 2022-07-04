<?php

class MedicalcheckupController
{
    private $printer;
    private $appointmentModel;
    private $mailer;

    public function __construct($appointmentModel, $printer, $mailer)
    {
        $this->appointmentModel = $appointmentModel;
        $this->printer = $printer;
        $this->mailer = $mailer;
    }

    public function execute()
    {
        $ap = $this->appointmentModel->getAppointment($_SESSION["nickname"]);

        if (!$ap) {
            Helper::redirect("/medicalcheckup/makeAppointmentForm");
        }

        Helper::redirect("/medicalcheckup/showAppointment?med={$ap['medicalCenter']}&d={$ap['date']}");
    }

    public function showAppointment()
    {
        $data['medicalCenter'] = $_GET['med'];
        $data['date'] = $_GET['d'];

        $this->printer->generateView('medicalcheckupView.html', $data);
    }

    public function makeAppointmentForm()
    {

        $data['medicalCenters'] = $this->appointmentModel->getMedicalCenters();
        $data['medicalCenter'] = isset($_GET['med']) ? $_GET['med'] : null;
        $data['date'] = isset($_GET['d']) ? $_GET['d'] : null;
        $data['errors'] = isset($_SESSION['errors']) ? $_SESSION['errors'] : null;
        unset($_SESSION['errors']);

        $this->printer->generateView('medicalcheckupFormView.html', $data);
    }

    public function makeAppointment()
    {
        $nickname = $_SESSION['nickname'];
        $date = $_POST['date'];
        $medicalCenter = $_POST['medicalCenter'];

        $errors = $this->formValidation($date, $medicalCenter);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            Helper::redirect("/medicalcheckup/makeAppointmentForm?med={$medicalCenter}&d={$date}");
        }

        $response = $this->appointmentModel->createAppointment($nickname, $date, $medicalCenter);

        if ($response) {
            $_SESSION['errors'] = $response;
            Helper::redirect("/medicalcheckup/makeAppointmentForm?med={$medicalCenter}&d={$date}");
        }


        Helper::redirect("/medicalcheckup/successfullAppointment?med={$medicalCenter}&d={$date}");
    }

    private function formValidation($date, $medicalCenter)
    {
        $error = [];

        if (!$this->isValidDate($date)) {
            $error[] = 'Ingrese una fecha correcta';
        }

        if (!$this->isValidMedicalCenter($medicalCenter)) {
            $error[] = 'Ingrese un centro medico';
        }

        return $error;
    }

    private function isValidDate($input)
    {
        $input = new DateTime($input);
        $now = new DateTime();
        $diff = (int)$now->diff($input)->format('%r%a');
        return $diff >= 0;
    }

    private function isValidMedicalCenter($input)
    {
        return $input != 0;
    }

    public function successfullAppointment()
    {
        $data['medicalCenter'] = $this->appointmentModel->getNameMedicalCenter($_GET['med']);
        $data['date'] = $_GET['d'];
        $_SESSION['flight_level'] = $this->appointmentModel->getFlightLevel($_SESSION['nickname']);


        $this->sendConfirmationEmail($_SESSION['email'], $_SESSION['nickname'], $data['medicalCenter'], $data['date']);

        if (isset($_SESSION['pausedBuy'])) {
            $data['pausedBuy'] = true;
        }

        $this->printer->generateView('medicalcheckupSuccessView.html', $data);
    }

    private function sendConfirmationEmail($to, $nickname, $medicalCenter, $date)
    {
        $subject = 'Confirmación de turno médico GauchoRocket';

        $message = "<h1>¡Hola, $nickname!</h1><p>Este es un email de confirmacion de turno .</p><p>Usted tiene turno el día $date en el centro médico de $medicalCenter}.</p><p>Recuerde que su realización es de caracter obligatorio para poder volar con Gaucho Rocket</p><p>Puede cancelar su turno ingresando aquí: <a href='http://localhost/medicalcheckup/deleteAppointment'>Cancelar turno</a></p>";

        $this->mailer->sendEmail($to, $subject, $message);
    }

    public function deleteAppointment()
    {
        $res = $this->appointmentModel->getAppointment($_SESSION['nickname']);
        $this->appointmentModel->deleteAppointment($_SESSION['nickname']);
        Helper::redirect("/medicalcheckup/appointmentDeleted?med={$res['medicalCenter']}&d={$res['date']}");
    }

    public function appointmentDeleted()
    {
        $data['medicalCenter'] = $_GET['med'];
        $data['date'] = $_GET['d'];

        $this->printer->generateView('medicalcheckupDeleteView.html', $data);
    }

    private function mapErrors($arr)
    {
        $res = [];
        foreach ($arr as $valor) {
            $res[] = ['error' => $valor];
        }
        return $res;
    }
}