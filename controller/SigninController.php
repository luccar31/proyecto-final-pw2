<?php

class SigninController {
    private $printer;
    private $userModel;

    public function __construct($userModel, $printer) {
        $this->userModel = $userModel;
        $this->printer = $printer;
    }

    public function execute(){
        $this->printer->generateView("signinView.html");
    }

    public function createUser(){
        $nickname = $_POST["nickname"];
        $password = $_POST["password"];
        $firstname = $_POST["firstname"];

        if(!$this->existsUser($nickname)){
            //todo: mandar confirmacion por mail
            $this->userModel->createUser($nickname,$password, $firstname);
            header("location: /login");
        }
        else{
            header("location: index");
        }

    }

    private function existsUser($nickname){
        return $this->userModel->getUserNickname($nickname) ? true : false;
    }
}