<?php

class LoginController {
    private $printer;
    private $userModel;
    private $clientModel;
    private $mailer;

    public function __construct($models, $printer, $mailer)
    {
        $this->userModel = $models["userModel"];
        $this->clientModel = $models["clientModel"];
        $this->printer = $printer;
        $this->mailer = $mailer;
    }

    public function execute(){
        $this->printer->generateView('loginView.html');
    }

    public function validateUser(){
        $nickname = $_POST["nickname"];
        $password = $_POST["password"];

        $user = $this->userModel->getUser($nickname, $password);
        $client = $this->clientModel->getClient($nickname);

        $_SESSION['user_firstname'] = $client[0]['firstname'];
        $_SESSION['user_surname'] = $client[0]['surname'];

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
        $_SESSION["admin"] = $this->userModel->isAdmin($nickname);
        $_SESSION["email"] = $this->clientModel->getEmail($nickname);
        Helper::redirect('/login/getVerificationState');
    }

    public function closeSession(){
        session_unset();
        session_destroy();
        Helper::redirect('/');
    }

    public function getVerificationState(){
        //se comprueba al inicio el estado de verificacion de la cuenta
        $this->updateSessionVerificationState();

        if(!$_SESSION['verified'] && !$_SESSION['admin']){
            //Helper::redirect('/login/sendVerificationEmail');
            $this->sendVerificationEmail();
        }
        elseif (isset($_SESSION['pausedBuy'])){
            Helper::redirect('/credit/payInfo');
        }

        Helper::redirect('/');
    }

    private function sendVerificationEmail(){
        //se comprueba al inicio el estado de verificacion de la cuenta
        $this->updateSessionVerificationState();

        //si esta verificada, no vuelve a mandar el email
        if($_SESSION['verified']){
            Helper::redirect('/login/verifiedAccount');
        }

        $verifCode = $this->getGenerateVerificationCode();

        $subject = 'Verificacion de cuenta Gaucho Rocket';
        $message = "<h1>¡Hola, {$_SESSION['nickname']}!</h1><p>Este es un mail de verificacion de cuenta.</p><p>Por favor, ingrese en el siguiente link para verificar su cuenta: <a href='http://localhost/login/verifyCode?verif=$verifCode'>Link de verificacion</a>.</p>";

        $this->mailer->sendEmail($_SESSION['email'], $subject, $message);

        $_SESSION['verifCode'] = $verifCode;

        Helper::redirect('/login/emailSentMessage');
    }

    public function emailSentMessage(){
        $data = ['email' => $_SESSION['email']];
        $this->printer->generateView('verificationEmailView.html', $data);
    }

    public function verifiedAccount(){
        //session_unset();
        unset($_SESSION['verifCode']);
        $this->printer->generateView('verificationStatusView.html');
    }

    private function getGenerateVerificationCode()
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($chars), 0, 30);
    }

    public function verifyCode(){

        //se comprueba al inicio el estado de verificacion de la cuenta
        $this->updateSessionVerificationState();

        //si esta verificada, no vuelve a mandar el email
        if($_SESSION['verified']){

            Helper::redirect('/login/verifiedAccount');
        }

        if($_GET['verif'] != $_SESSION['verifCode']){
            Helper::redirect('/login/verificationFail');
        }

        $this->clientModel->updateVerificationState($_SESSION['nickname']);

        Helper::redirect('/login/verificationSuccess');
    }

    public function verificationSuccess(){
        unset($_SESSION['verifCode']);
        $this->printer->generateView('verificationSuccessView.html');
    }

    public function verificationFail(){
        $this->printer->generateView('verificationFailView.html');
    }

    private function updateSessionVerificationState(){
        $_SESSION['verified'] = $this->clientModel->getVerificationState($_SESSION['nickname']);
    }
}