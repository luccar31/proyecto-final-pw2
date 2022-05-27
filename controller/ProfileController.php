<?php

class ProfileController {
    private $printer;
    private $userModel;
    private $clientModel;

    public function __construct($models, $printer) {
        $this->userModel = $models["userModel"];
        $this->clientModel = $models["clientModel"];
        $this->printer = $printer;
    }

    public function execute(){
        $client = $this->clientModel->getClient($_SESSION["nickname"]);
        $data = ["client" => $client];
        $this->printer->generateView('profileView.html', $data);
    }
}