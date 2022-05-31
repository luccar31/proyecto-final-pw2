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
        $data = ['nickname' => $nickname, 'password' => $password, 'repeatPassword' => $repeatPassword, 'firstname' => $firstname, 'surname' => $surname, 'email' => $email];

        //le mando los datos para que valide y me retorna la vista y datos a mostrar
        $data = $this->userModel->createUser2($data, $this->clientModel);

        //la vista puede ser signinView.html con los datos en caso de error, o loginView.html si salió todo ok
        $this->printer->generateView($data['view'], $data);


    }
}