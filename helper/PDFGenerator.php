<?php
require_once ('third-party/dompdf/autoload.inc.php');

use Dompdf\Dompdf;

class PDFGenerator extends Dompdf
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function getPDF($html){
        $this->loadHtml($html);
        $this->setPaper('A4');
        $this->render();
        $this->stream("Reserva-de-vuelo.pdf");
    }
}