<?php

class ReportController
{
    private $userModel;
    private $reportModel;
    private $printer;
    private $printerPDF;
    private $pdf;

    public function __construct($models, $printer, $printerPDF, $pdf)
    {
        $this->userModel = $models["userModel"];
        $this->reportModel = $models["reportModel"];
        $this->printer = $printer;
        $this->printerPDF = $printerPDF;
        $this->pdf = $pdf;
    }

    public function execute(){
        $this->printer->generateView('reportView.html');
    }

    public function report1(){ //ocupacion por viaje y equipo
        Helper::debugExit('Nada');
    }

    public function report2(){ //
        $year = $_GET['y'];
        $data = $this->reportModel->monthlyBilling($year);

        if(!$data['totals']){
            echo 'No hay nada para mostrar uwu';
            exit();
        }

        $graph = new Graph(600,600,'auto');
        $graph->SetScale("textlin");

        $tickPositions = $this->getTickPositions($data['totals']);
        $graph->yaxis->SetTickPositions($tickPositions);
        $graph->SetMargin(60,50,40,40);
        $graph->SetBox(true);
        $graph->ygrid->SetFill(false);
        $tickLabels = $data['months'];
        $graph->xaxis->SetTickLabels($tickLabels);

        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        $b1plot = new BarPlot($data['totals']);
        $b1plot->SetWidth(40);
        //$gbplot = new GroupBarPlot(array($b1plot));
        $graph->Add($b1plot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor($this->rand_color());

        $graph->title->Set("Facturacion mensual del año $year");

        $graph->Stroke();
    }

    public function report3(){ //cabina mas vendida
        $response = $this->reportModel->mostSoldCabin();
        Helper::debugExit($response);
    }

    public function report4(){ //facturacion por cliente
        $response = $this->reportModel->billingPerClient();
        Helper::debugExit($response);
    }

    private function getTickPositions($data)
    {
        $max = (int)ceil(max($data));
        $intervals = 10;
        $intervalLength = (int)floor($max / 10);

        $positions = [];

        for ($i = 1; $i < $intervals; $i++){
            $pos = $intervalLength * $i;
            $positions[] = $pos;
        }
        $positions[] = $max;

        return $positions;
    }

    private function rand_color() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

}