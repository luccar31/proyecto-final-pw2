<?php

class CreditController {
    private $printer;
    private $creditModel;

    public function __construct($models, $printer) {
        $this->creditModel = $models["creditModel"];
        $this->printer = $printer;
    }

    public function execute(){
        Helper::redirect('/');
    }

    //info de los precios del vuelo elegido
    public function payInfo(){

        $data['id_type_cabin'] = $_POST['type_cabin'];
        $data['id_service']  = $_POST['service'];
        $data['num_tickets']  =  $_POST['num_tickets'];

        $_SESSION['id_type_cabin'] = $_POST['type_cabin'];
        $_SESSION['id_service']  = $_POST['service'];
        $_SESSION['num_tickets']  =  $_POST['num_tickets'];

        $data['price'] = $this->creditModel->calculatePrice($_SESSION['id_flight_plan'], $data['num_tickets'], $data['id_service'], $data['id_type_cabin']);


        $_SESSION['totalPrice'] = $data['price']['totalPrice'];

        $this->printer->generateView('priceView.html', $data);


    }

    public function pay(){

        $data['firstname'] = $_SESSION['user_firstname'];
        $data['surname'] = $_SESSION['user_surname'];
        $data['totalPrice'] = $_POST['totalPrice'];

        $status = $this->creditModel->validateTotalPrice($data['totalPrice']);

        if ($status){
            $this->printer->generateView('payView.html', $data);
        }
        else{
            echo "Hubo un error";
        }

    }

    public function confirmPay(){

        $data['totalPrice'] = $_POST['totalPrice'];

        $status = $this->creditModel->validateTotalPrice($data['totalPrice']);

        if ($status){
            Helper::redirect('/ticket/createTicket');        }
        else{
            $data['payError'] = 'Disculpe. Hubo un error en la transacción';
            $this->printer->generateView('payView.html', $data);
        }

    }
}