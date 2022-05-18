<?php

class ProfileController {
    private $printer;
    private $userModel;

    public function __construct($userModel, $printer) {
        $this->userModel = $userModel;
        $this->printer = $printer;
    }

    public function execute(){
        $this->printer->generateView('profileView.html');
    }
}