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
        $nickname = $_POST["nickname"];
        $password = $_POST["password"];

        $data = $this->userModel->getUser($nickname, $password);

        if($data){
            $this->startSession();
            header("location: http://localhost/");
            exit();
        }
        else{
            $this->printer->generateView('loginView.html',
                                        ['nickname' => $nickname,'password' => $password,
                                         'error' => "Usuario/contraseña incorrecto"]);
        }
    }

    private function startSession(){
        session_start();
        $_SESSION["logged"] = true;
    }

    public function closeSession(){
        session_unset();
        session_destroy();
        header("location: http://localhost/");
        exit();
    }
}