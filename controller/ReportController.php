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

    public function report2(){ //facturacion mensual
        $data = $this->reportModel->monthlyBilling();

        $graph = new Graph(600,600,'auto');
        $graph->SetScale("textlin");

        $tickPositions = array(10000,20000,30000,50000, 60000, 70000, 80000, 90000, 100000, 40000);
        $graph->yaxis->SetTickPositions($tickPositions);
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels(array('Ene','Feb','Mar','Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov' , 'Dic'));
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        $b1plot = new BarPlot($data);
        $gbplot = new GroupBarPlot(array($b1plot));
        $graph->Add($gbplot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#cc1111");

        $graph->title->Set("Bar Plots");

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

}