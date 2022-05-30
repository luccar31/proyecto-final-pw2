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


        //por cada validacion agregamos un elemento error al data para mostrarlo en pantalla

        if (empty($nickname) || strlen($nickname) > 50) {
            $data['nicknameError'] = 'El nombre de usuario debe tener entre 1 y 50 caracteres';
        }
        if ($this->existsUserNickname($nickname)) {
            $data['duplicateNicknameError'] = 'El nombre de usuario ya existe. Intente con otro';
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
            $data['emailError'] = 'El email debe tener entre 1 y 50 caracteres';
        }
        if ($this->existsUserEmail($email)) {
            $data['duplicateEmailError'] = 'El email ya se encuentra en nuestra base de datos. Intente con otro';
        }

        //cuento si en el data (además de los 5 elementos existenes) hay algun elemento de error añadido para generar la vista
        if (count($data) > 5) {
            $this->printer->generateView('signinView.html', $data);
        }

        //de lo contrario, si está bien, creo el usuario
        else {
            //todo: mandar confirmacion por mail
            $this->userModel->createUser($nickname, $password);
            $this->clientModel->createClient($nickname, $firstname, $surname, $email);
            header("location: /login");
            exit();
        }

    }

    private function existsUserNickname($nickname)
    {
        return $this->userModel->getUserNickname($nickname) ? true : false;
    }

    private function existsUserEmail($email)
    {
        return $this->userModel->getUserEmail($email) ? true : false;
    }

    private function redirect($url)
    {
        header("location: " . $url);
        exit();
    }

    /* otra vista
    public function successfullSignin(){
        $this->printer->generateView("signinView.html");
    }
    */
}