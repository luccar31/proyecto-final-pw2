<?php

class LoginController {
    private $printer;
    private $userModel;

    public function __construct($userModel, $printer) {
        $this->userModel = $userModel;
        $this->printer = $printer;
    }

    public function execute(){
        $this->printer->generateView('loginView.html');
    }

    public function validateUser(){
        $nickname = $_POST("nickname");
        $password = $_POST("password");

        $data = $this->userModel->getUser($nickname, $password);

        if($data){
            session_start();
            $_SESSION["nickname"] = $nickname;
        }
        header("location: index");
    }
}