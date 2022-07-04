<?php

require_once('third-party/dompdf/autoload.inc.php');

use Dompdf\Dompdf;

class PDFGenerator extends Dompdf
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->getOptions()->setChroot('/');
    }

    public function getPDF($html, $filename)
    {
        $this->loadHtml($html);
        $this->setPaper('A4');
        $this->render();
        $this->stream($filename . '.pdf');
    }
}