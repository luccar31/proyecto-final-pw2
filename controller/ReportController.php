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

        $tickPositions = array(100000,200000,300000,400000, 500000, 600000, 700000, 800000, 900000, 1000000);
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
        $data = $this->reportModel->mostSoldCabin();
//        Helper::debugExit($data);

        $graph = new Graph(650,600,'auto');
        $graph->SetScale("textlin",0,65);


        $graph->yaxis->SetTickPositions(array(0,5,15,25,35,45),array(10,15,20,30,40));
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels(array('Turista','Ejecutivo','Primera'));
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        $b1plot = new BarPlot($data);
        $gbplot = new GroupBarPlot(array($b1plot));
        $graph->Add($gbplot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#cc1111");

        $graph->title->Set("Venta de tickets por cabina");

        $graph->Stroke();
    }


    public function report4(){ //facturacion por cliente
        $result = $this->reportModel->billingPerClient();

        $suma = array_column($result, 'suma');
        $cliente = array_column($result, 'user_nickname');

// Some data
$data = $suma;

// A new pie graph
$graph = new PieGraph(900,900,'auto');

// Don't display the border
$graph->SetFrame(false);

// Setup title
$graph->title->Set("Facturación por cliente");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,18);
$graph->title->SetMargin(8); // Add a little bit more margin from the top

// Create the pie plot
$p1 = new PiePlotC($data);

// Set size of pie
$p1->SetSize(0.43);

// Label font and color setup
$p1->value->SetFont(FF_ARIAL,FS_BOLD,12);
$p1->value->SetColor('white');

$p1->value->Show();



// Use percentage values in the legends values (This is also the default)
$p1->SetLabelType(PIE_VALUE_PER);


        for ($i=0; $i < sizeof($result); $i++){

            $array[] = $result[$i]['user_nickname'] . "\n" . "$" . $result[$i]['suma'];
        }


$lbl = $array;
$p1->SetLabels($lbl);


// Add drop shadow to slices
$p1->SetShadow();


// Add plot to pie graph
$graph->Add($p1);

// .. and send the image on it's marry way to the browser
$graph->Stroke();





    }

}