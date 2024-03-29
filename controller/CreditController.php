<?php

class CreditController
{
    private $printer;
    private $creditModel;

    public function __construct($models, $printer)
    {
        $this->creditModel = $models["creditModel"];
        $this->printer = $printer;
    }

    public function execute()
    {
        Helper::redirect('/');
    }

    //info de los precios del vuelo elegido
    public function payInfo()
    {
        $data['price'] = $this->creditModel->calculatePrice($_SESSION['id_flight_plan'], $_SESSION['num_tickets'], $_SESSION['service'], $_SESSION['type_cabin']);

        $_SESSION['totalPrice'] = $data['price']['totalPrice'];

        $this->printer->generateView('priceView.html', $data);
    }

    public function pay()
    {

        $data['firstname'] = $_SESSION['user_firstname'];
        $data['surname'] = $_SESSION['user_surname'];
        $data['totalPrice'] = $_SESSION['totalPrice'];
        $this->printer->generateView('payView.html', $data);

    }

    public function confirmPay()
    {


        $titular = $_POST['titular'];
        $nroTarjeta = $_POST['nroTarjeta'];

        if (empty($titular) || empty($nroTarjeta) || strlen($nroTarjeta) != 16 || empty($_POST['ccv']) || empty($_POST['vencimiento'])) {
            $data['firstname'] = $_SESSION['user_firstname'];
            $data['surname'] = $_SESSION['user_surname'];
            $data['error'] = 'Los datos ingresados son incorrectos';
            $this->printer->generateView('payView.html', $data);
        } else {

            $this->creditModel->registerPayment($titular, $nroTarjeta, $_SESSION['totalPrice'], $_SESSION['nickname']);
            Helper::redirect('/ticket/createTicket');
        }


    }

}