<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('helper/Session.php');
include_once('controller/HomeController.php');
include_once('controller/SigninController.php');
include_once('controller/LoginController.php');
include_once('controller/ProfileController.php');
include_once('controller/MedicalcheckupController.php');
include_once('controller/FlightController.php');
include_once('model/UserModel.php');
include_once('model/ClientModel.php');
include_once('model/AppointmentModel.php');
include_once('model/FlightModel.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');

class Configuration {

    public function getSigninController() {
        return new SigninController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter());
    }

    public function getProfileController() {
        return new ProfileController(['userModel' => $this->getUserModel(), 'clientModel' => $this->getClientModel()], $this->getPrinter());
    }

    public function getLoginController() {
        return new LoginController($this->getUserModel(), $this->getPrinter());
    }

    public function getHomeController() {
        return new HomeController($this->getPrinter());
    }

    public function getMedicalcheckupController(){
      return new MedicalcheckupController( $this->getAppointmentModel(), $this->getPrinter());
    }
    public function getFlightController() {
        return new FlightController($this->getFlightModel(),$this->getPrinter());
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

    private function getFlightModel(){
        return new FlightModel($this->getDatabase());
    }

    private function getDatabase() {
       return new MySqlDatabase(
            'localhost',
            'root',
            '',
            'gauchorocket');

    }

    private function getPrinter() {
        return new MustachePrinter("view", new Session());
    }

    public function getRouter() {
        return new Router($this, "getHomeController", "execute", new Session());
    }
}