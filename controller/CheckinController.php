<?php

class CheckinController
{
    private $ticketModel;
    private $printer;
    private $printerPDF;
    private $mailer;
    private $qr;
    private $pdf;

    public function __construct($models, $printer, $printerPDF, $qr, $pdf, $mailer) {
        $this->ticketModel = $models['ticketModel'];
        $this->printer = $printer;
        $this->printerPDF = $printerPDF;
        $this->qr = $qr;
        $this->pdf = $pdf;
        $this->mailer = $mailer;
    }

    public function execute(){
        Helper::redirect('/');
    }

    public function confirmTicketReservation(){

        if ($_SESSION['checkInByTicket'] != $_GET['id_ticket']){

            $_SESSION['checkInByTicket'] = $_GET['id_ticket'];
                $id_ticket = $_GET['id_ticket'];
                $nickname = $_SESSION['nickname'];

                $boardingCode = $this->ticketModel->createBoardingCode($id_ticket, $nickname);

                Helper::redirect("/checkin/generateQR?bcode='$boardingCode'&id_ticket='$id_ticket'");
        }
        else{
            Helper::redirect("/ticket/showClientTickets");
        }
    }

    public function generateQR(){

        $code = $_GET['bcode'];
        $content = 'Codigo de abordaje: '.$code;

        $filepath = $this->qr->getQrPng($content);

        if (!$filepath){
            echo 'No se pudo generar el QR';
            exit();
        }

        $data = ['code' => $code, 'filepath' => $filepath];

        $html = $this->printerPDF->generateTemplatedStringForPDF('templateAbordingPDF.html', $data);
        $this->pdf->getPDF($html, 'ConfirmacionReserva');

        $html = $this->printerPDF->generateTemplatedStringForPDF('templateAbordingMail.html', $data);
        $this->mailer->sendEmail($_SESSION['email'], 'Codigo de abordaje', $html);
    }
}