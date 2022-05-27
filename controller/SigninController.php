<?php

class SigninController {
    private $printer;
    private $userModel;
    private $clientModel;

    public function __construct($models, $printer) {
        $this->userModel = $models["userModel"];
        $this->clientModel = $models["clientModel"];
        $this->printer = $printer;
    }

    public function execute(){
        $this->printer->generateView("signinView.html");
    }

    public function createUser(){
        $nickname = $_POST["nickname"];
        $password = $_POST["password"];
        $firstname = $_POST["firstname"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];

        if(!$this->existsUser($nickname)){
            //todo: mandar confirmacion por mail
            $this->userModel->createUser($nickname, $password);
            $this->clientModel->createClient($nickname, $firstname, $surname, $email);
            header("location: /login");
        }
        else{
            header("location: index");
        }
    }

    private function existsUser($nickname){
        return $this->userModel->getUserNickname($nickname) ? true : false;
    }

    /* otra vista
    public function successfullSignin(){
        $this->printer->generateView("signinView.html");
    }
    */
}