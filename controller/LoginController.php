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

        if($user){
            $this->startSession($nickname);
            $this->redirect("http://localhost/");
        }
        else{
            $this->printer->generateView('loginView.html',
                                        ['nickname' => $nickname,'password' => $password,
                                         'error' => "Usuario o contraseña incorrecto"]);
        }
    }

    private function startSession($nickname){
        session_start();
        $_SESSION["logged"] = true;
        $_SESSION["nickname"] = $nickname;
    }

    public function closeSession(){
        session_unset();
        session_destroy();
        $this->redirect("http://localhost/");
    }

    //funcion aplicable para todos los controladores
    //se repite codigo
    private function redirect($url){
        header("location: " . $url);
        exit();
    }
}