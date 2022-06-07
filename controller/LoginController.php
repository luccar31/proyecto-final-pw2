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

        $user = $this->userModel->getUser($nickname, $password);

        if(!$user){
            $data = ['nickname' => $nickname,'password' => $password, 'error' => "Usuario o contraseña incorrecto"];
            return $this->printer->generateView('loginView.html', $data);
        }

        $this->startSession($nickname);
        Helper::redirect('/');
        return 1;
    }

    private function startSession($nickname){
        session_start();
        $_SESSION["logged"] = true;
        $_SESSION["nickname"] = $nickname;
    }

    public function closeSession(){
        session_unset();
        session_destroy();
        Helper::redirect('/');
    }
}