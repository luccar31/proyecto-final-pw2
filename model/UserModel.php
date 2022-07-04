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
        $password = hash('md5', $password);
        $this->database->query("INSERT INTO user (nickname, password, role)
                                VALUES ('$nickname', '$password', 1)");
    }

    public function getUser($nickname, $password)
    {
        $password = hash('md5', $password);
        return $this->database->query("SELECT nickname FROM user
                                       WHERE nickname='$nickname'
                                       AND password='$password'");
    }

    public function validateData($data, $previousData)
    {

        if (!empty($this->getUserNickname($data['nickname']))) {
            $data['duplicateNicknameError'] = 'El nombre de usuario ya existe. Intente con otro';
        }

        if (!empty($this->getUserEmail($data['email']))) {
            $data['duplicateEmailError'] = 'El email ya se encuentra en nuestra base de datos. Intente con otro';
        }

        //cuento si en el data (además de los 5 elementos existenes) hay algun elemento de error añadido
        //de ser asi, devuelvo un elemento validData en el array que sea false
        if (count($data) > $previousData) {
            $data['isValid'] = false;
        } //de lo contrario, si está bien, devuelvo el elemento validData del array pero en true
        else {
            $data['isValid'] = true;
        }

        return $data;

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

    public function isAdmin($user_nickname)
    {
        $role = (int)$this->database->query("SELECT role FROM user WHERE nickname = '$user_nickname'")[0]['role'];
        return $role == 2;
    }
}