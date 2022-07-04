<?php

require_once('helper/MySqlDatabase.php');
require_once('helper/Router.php');
require_once('helper/Session.php');
require_once('helper/Helper.php');
require_once('helper/MustachePrinter.php');
require_once('helper/Mailer.php');
require_once('helper/PDFGenerator.php');
require_once('helper/QRGenerator.php');

require_once('controller/HomeController.php');
require_once('controller/SigninController.php');
require_once('controller/LoginController.php');
require_once('controller/ProfileController.php');
require_once('controller/MedicalcheckupController.php');
require_once('controller/Flight_planController.php');
require_once('controller/TicketController.php');
require_once('controller/CreditController.php');
require_once('controller/CheckinController.php');
require_once('controller/ReportController.php');

require_once('model/UserModel.php');
require_once('model/ClientModel.php');
require_once('model/AppointmentModel.php');
require_once('model/TicketModel.php');
require_once('model/Flight_planModel.php');
require_once('model/CreditModel.php');
require_once('model/CheckinModel.php');
require_once('model/ReportModel.php');

require_once('third-party/jpgraph/src/jpgraph.php');
require_once('third-party/jpgraph/src/jpgraph_pie.php');
require_once('third-party/jpgraph/src/jpgraph_bar.php');
require_once('third-party/jpgraph/src/jpgraph_pie3d.php');


class Configuration
{

    public function getSigninController()
    {
        return new SigninController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter());
    }

    public function getProfileController()
    {
        return new ProfileController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter());
    }

    public function getLoginController()
    {
        return new LoginController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter(), $this->getMailer());
    }

    public function getHomeController()
    {
        return new HomeController($this->getPrinter(), $this->getFlight_planModel());
    }

    public function getMedicalcheckupController()
    {
        return new MedicalcheckupController($this->getAppointmentModel(), $this->getPrinter(), $this->getMailer());
    }

    public function getTicketController()
    {
        return new TicketController(['userModel' => $this->getUserModel(), 'flight_planModel' => $this->getFlight_planModel(), 'ticketModel' => $this->getTicketModel(), 'appointmentModel' => $this->getAppointmentModel()], $this->getPrinter(), $this->getPrinterForPDF(), $this->getPDFGenerator());
    }

    public function getFlight_planController()
    {
        return new Flight_planController(['flight_planModel' => $this->getFlight_planModel()], $this->getPrinter());
    }

    public function getCreditController()
    {
        return new CreditController(['creditModel' => $this->getCreditModel()], $this->getPrinter());
    }

    public function getCheckinController()
    {
        return new CheckinController(['ticketModel' => $this->getTicketModel()], $this->getPrinter(), $this->getPrinterForPDF(), $this->getQRGenerator(), $this->getPDFGenerator(), $this->getMailer());
    }

    public function getReportController()
    {
        return new ReportController(['reportModel' => $this->getReportModel(), 'userModel' => $this->getUserModel()], $this->getPrinter(), $this->getPrinterForPDF(), $this->getPDFGenerator());
    }

    private function getCheckinModel()
    {
        return new CheckinModel($this->getDatabase());
    }

    private function getCreditModel()
    {
        return new CreditModel($this->getDatabase());
    }

    private function getUserModel()
    {
        return new UserModel($this->getDatabase());
    }

    private function getClientModel()
    {
        return new ClientModel($this->getDatabase());
    }

    private function getAppointmentModel()
    {
        return new AppointmentModel($this->getDatabase());
    }

    private function getFlight_planModel()
    {
        return new Flight_planModel($this->getDatabase());
    }

    private function getTicketModel()
    {
        return new TicketModel($this->getDatabase());
    }

    private function getReportModel()
    {
        return new ReportModel($this->getDatabase());
    }

    private function getDatabase()
    {
        return new MySqlDatabase(
            'localhost',
            'root',
            '',
            'gauchorocket');

    }

    private function getPrinter()
    {
        return new MustachePrinter("view");
    }

    private function getPrinterForPDF()
    {
        return new MustachePrinter("assets/templates");
    }

    public function getRouter()
    {
        return new Router($this, "getHomeController", "execute");
    }

    public function getMailer()
    {
        return new Mailer(); //en realidad todas las configuraciones deberian ir acá
    }

    public function getPDFGenerator()
    {
        return new PDFGenerator();
    }

    public function getQRGenerator()
    {
        return new QRGenerator('assets/qr/');
    }
}