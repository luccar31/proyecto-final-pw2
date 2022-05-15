<?php

class SarasaController{

    private $printer;
    private $sarasaModel;

    public function __construct($sarasaModel, $printer) {
        $this->sarasaModel = $sarasaModel;
        $this->printer = $printer;
    }

    public function execute() {
        $this->printer->generateView('sarasaView.html');
    }

}