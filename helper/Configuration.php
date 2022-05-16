<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('controller/SarasaController.php');
include_once('controller/SigninController.php');
include_once('controller/LoginController.php');
include_once('model/UserModel.php');
include_once('model/SarasaModel.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');

class Configuration {

    public function getSigninController() {
        return new SigninController($this->getUserModel(), $this->getPrinter());
    }

    public function getLoginController() {
        return new LoginController($this->getUserModel(), $this->getPrinter());
    }

    public function getSarasaController() {
        return new SarasaController($this->getSarasaModel(), $this->getPrinter());
    }

    private function getSarasaModel(){
        return new SarasaModel($this->getDatabase());
    }

    private function getUserModel(){
        return new UserModel($this->getDatabase());
    }

    private function getDatabase() {
       return new MySqlDatabase(
            'localhost',
            'root',
            '',
            'sarasa');

    }

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getSarasaController", "execute");
    }
}