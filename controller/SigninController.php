<?php

class SigninController
{
    private $printer;
    private $userModel;
    private $clientModel;

    public function __construct($models, $printer)
    {
        $this->userModel = $models["userModel"];
        $this->clientModel = $models["clientModel"];
        $this->printer = $printer;
    }

    public function execute()
    {
        $this->printer->generateView("signinView.html");
    }

    public function createUser()
    {
        $nickname = $_POST["nickname"];
        $password = $_POST["password"];
        $repeatPassword = $_POST["repeatPassword"];
        $firstname = $_POST["firstname"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];

        //datos ingresados anteriormente
        $data = ['nickname' => $nickname, 'password' => $password, 'firstname' => $firstname, 'surname' => $surname, 'email' => $email];
        $previousData = count($data);

        //por cada validacion agregamos un elemento error al data para mostrarlo en pantalla
        if (empty($nickname) || strlen($nickname) > 50) {
            $data['nicknameError'] = 'El nombre de usuario debe tener entre 1 y 50 caracteres';
        }

        if (empty($password) || strlen($password) > 50) {
            $data['passwordError'] = 'La contraseña debe tener entre 1 y 50 caracteres';
        }
        if ($password != $repeatPassword) {
            $data['repeatPasswordError'] = 'Las contraseñas no coinciden';
        }
        if (empty($firstname) || strlen($firstname) > 50) {
            $data['firstnameError'] = 'El nombre debe tener entre 1 y 50 caracteres';
        }
        if (empty($surname) || strlen($surname) > 50) {
            $data['surnameError'] = 'El apellido debe tener entre 1 y 50 caracteres';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['emailError'] = 'El email debe tener un formato válido';
        }

        //le mando los datos para que valide y me retorna si es valido o no
        $data = $this->userModel->validateData($data, $previousData);

        if ($data['isValid'] == false){
            $this->printer->generateView('signinView.html', $data);

        }
        else{
            $this->userModel->createUser($nickname, $password);
            $this->clientModel->createClient($nickname, $firstname, $surname, $email);
            $_SESSION['nickname'] = $nickname;
            $_SESSION['email'] = $email;
            Helper::redirect('/login');
        }
    }
}