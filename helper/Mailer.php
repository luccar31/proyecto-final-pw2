<?php

require_once ('third-party/PHPMailer/src/Exception.php');
require_once ('third-party/PHPMailer/src/PHPMailer.php');
require_once ('third-party/PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer extends PHPMailer
{
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);
        $this->config();
    }

    private function config(){
        $this->isSMTP();
        $this->SMTPAuth = true;
        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->Host = 'smtp.gmail.com';
        $this->Port = 465;
        $this->Username = 'gauchorocket666@gmail.com';
        $this->Password = 'ybedlrvljddlsyzf'; //htXOnhWHyKmVe5nO
        $this->isHTML();
    }

    public function sendEmail($to, $subject = '', $body = ''){
        try {
            $this->addAddress($to);
            $this->Subject = $subject;
            $this->Body = $body;
            $this->send();
        } catch (Exception $e) {
            echo "Error: {$this->ErrorInfo}";
        }
    }
}