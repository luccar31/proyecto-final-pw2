<?php

include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
include_once('helper/Session.php');
include_once('helper/Helper.php');

include_once('controller/HomeController.php');
include_once('controller/SigninController.php');
include_once('controller/LoginController.php');
include_once('controller/ProfileController.php');
include_once('controller/MedicalcheckupController.php');
include_once('controller/Flight_planController.php');
include_once('controller/TicketController.php');

include_once('model/UserModel.php');
include_once('model/ClientModel.php');
include_once('model/AppointmentModel.php');
include_once('model/TicketModel.php');
include_once('model/Flight_planModel.php');

require_once('helper/MustachePrinter.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');
require_once('helper/Mailer.php');

class Configuration {

    public function getSigninController() {
        return new SigninController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter(), $this->getMailer());
    }

    public function getProfileController() {
        return new ProfileController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter());
    }

    public function getLoginController() {
        return new LoginController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter(), $this->getMailer());
    }

    public function getHomeController() {
        return new HomeController($this->getPrinter(), $this->getFlight_planModel());
    }

    public function getMedicalcheckupController(){
      return new MedicalcheckupController($this->getAppointmentModel(), $this->getPrinter());
    }

    public function getTicketController() {
        return new TicketController(['userModel' => $this->getUserModel(), 'flight_planModel' => $this->getFlight_planModel(), 'ticketModel' => $this->getTicketModel(), 'appointmentModel' => $this->getAppointmentModel()], $this->getPrinter());
    }

    public function getFlight_planController() {
        return new Flight_planController(['flight_planModel' => $this->getFlight_planModel()],$this->getPrinter());
    }

    private function getUserModel(){
        return new UserModel($this->getDatabase());
    }

    private function getClientModel(){
        return new ClientModel($this->getDatabase());
    }

    private function getAppointmentModel(){
        return new AppointmentModel($this->getDatabase());
    }

    private function getFlight_planModel(){
        return new Flight_planModel($this->getDatabase());
    }

    private function getTicketModel(){
        return new TicketModel($this->getDatabase());
    }

    private function getDatabase() {
       return new MySqlDatabase(
            'localhost',
            'root',
            '',
            'gauchorocket');

    }

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getHomeController", "execute");
    }

    public function getMailer() {
        return new Mailer(); //en realidad todas las configuraciones deberian ir acá
    }
}