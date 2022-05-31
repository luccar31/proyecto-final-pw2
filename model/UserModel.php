<?php

class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function createUser($nickname, $password)
    {
        $this->database->query("INSERT INTO user (nickname, password, role)
                                VALUES ('$nickname', '$password', 1)");
    }

    public function getUser($nickname, $password)
    {
        return $this->database->query("SELECT nickname FROM user
                                       WHERE nickname='$nickname'
                                       AND password='$password'");
    }

    public function getUserNickname($nickname)
    {
        return $this->database->query("SELECT nickname FROM user
                                       WHERE nickname='$nickname'");
    }

    public function getUserEmail($email)
    {
        return $this->database->query("SELECT email FROM client
                                       WHERE email='$email'");
    }

    public function createClient($nickname, $firstname, $surname, $email){
        $this->database->query("INSERT INTO client VALUES ('$nickname', '$firstname', '$surname', '$email', NULL, NULL)");
    }

    public function createUser2($data)
    {
        //por cada validacion agregamos un elemento error al data para mostrarlo en pantalla
        if (empty($data['nickname']) || strlen($data['nickname']) > 50) {
            $data['nicknameError'] = 'El nombre de usuario debe tener entre 1 y 50 caracteres';
        }
        if (!empty($this->getUserNickname($data['nickname']))) {
            $data['duplicateNicknameError'] = 'El nombre de usuario ya existe. Intente con otro';
        }
        if (empty($data['password']) || strlen($data['password']) > 50) {
            $data['passwordError'] = 'La contraseña debe tener entre 1 y 50 caracteres';
        }
        if ($data['password'] != $data['repeatPassword']) {
            $data['repeatPasswordError'] = 'Las contraseñas no coinciden';
        }
        if (empty($data['firstname']) || strlen($data['firstname']) > 50) {
            $data['firstnameError'] = 'El nombre debe tener entre 1 y 50 caracteres';
        }
        if (empty($data['surname']) || strlen($data['surname']) > 50) {
            $data['surnameError'] = 'El apellido debe tener entre 1 y 50 caracteres';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['emailError'] = 'El email debe tener entre 1 y 50 caracteres';
        }
        if (!empty($this->getUserEmail($data['email']))) {
            $data['duplicateEmailError'] = 'El email ya se encuentra en nuestra base de datos. Intente con otro';
        }

        //cuento si en el data (además de los 6 elementos existenes) hay algun elemento de error añadido para generar la vista
        if (count($data) > 6) {
            $data['view'] = 'signinView.html';
        } //de lo contrario, si está bien, creo el usuario
        else {
            //todo: mandar confirmacion por mail
            $this->createUser($data['nickname'], $data['password']);
            $this->createClient($data['nickname'], $data['firstname'], $data['surname'], $data['email']);
            $data['view'] = 'loginView.html';
        }
        return $data;

    }
}