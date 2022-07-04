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

    public function execute()
    {
        if (!$_SESSION['admin']) {
            helper::redirect('/');
        }
        $this->printer->generateView('reportView.html');
    }

    public function report1()
    {
        if (!$_SESSION['admin']) {
            helper::redirect('/');
        }

        $this->printer->generateView('noReportView.html');
    }

    public function report2()
    {
        if (!$_SESSION['admin']) {
            helper::redirect('/');
        }

        $year = $_GET['y'];
        $data = $this->reportModel->monthlyBilling($year);

        if (!$data['totals']) {
            echo 'No hay nada para mostrar uwu';
            exit();
        }

        $graph = new Graph(600, 600, 'auto');
        $graph->SetScale("textlin");

        $tickPositions = $this->getTickPositions($data['totals']);
        $graph->yaxis->SetTickPositions($tickPositions);
        $graph->SetMargin(60, 50, 40, 40);
        $graph->SetBox(true);
        $graph->ygrid->SetFill(false);
        $tickLabels = $data['months'];
        $graph->xaxis->SetTickLabels($tickLabels);

        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);

        $b1plot = new BarPlot($data['totals']);
        $b1plot->SetWidth(40);
        $graph->Add($b1plot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor($this->rand_color());

        $graph->title->Set("Facturacion mensual del año $year");

        $graph->Stroke();
    }

    private function getTickPositions($data)
    {
        $max = (int)ceil(max($data));
        $intervals = 10;
        $intervalLength = (int)floor($max / 10);

        $positions = [];

        for ($i = 1; $i < $intervals; $i++) {
            $pos = $intervalLength * $i;
            $positions[] = $pos;
        }
        $positions[] = $max;

        return $positions;
    }

    private function rand_color()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public function report3()
    {
        if (!$_SESSION['admin']) {
            helper::redirect('/');
        }

        $data = $this->reportModel->mostSoldCabin();

        $graph = new Graph(650, 600, 'auto');
        $graph->SetScale("textlin", 0, 65);


        $graph->yaxis->SetTickPositions(array(0, 5, 15, 25, 35, 45), array(10, 15, 20, 30, 40));
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels(array('Turista', 'Ejecutivo', 'Primera'));
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);

        $b1plot = new BarPlot($data);
        $gbplot = new GroupBarPlot(array($b1plot));
        $graph->Add($gbplot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#cc1111");

        $graph->title->Set("Venta de tickets por cabina");

        $graph->Stroke();
    }

    public function report4()
    {
        if (!$_SESSION['admin']) {
            helper::redirect('/');
        }

        $result = $this->reportModel->billingPerClient();

        $suma = array_column($result, 'suma');
        $cliente = array_column($result, 'user_nickname');

        $data = $suma;

        $graph = new PieGraph(900, 900, 'auto');

        $graph->SetFrame(false);

        $graph->title->Set("Facturación por cliente");
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 18);
        $graph->title->SetMargin(8);

        $p1 = new PiePlotC($data);

        $p1->SetSize(0.43);

        $p1->value->SetFont(FF_ARIAL, FS_BOLD, 12);
        $p1->value->SetColor('white');

        $p1->value->Show();

        $p1->SetLabelType(PIE_VALUE_PER);

        for ($i = 0; $i < sizeof($result); $i++) {

            $array[] = $result[$i]['user_nickname'] . "\n" . "$" . $result[$i]['suma'];
        }

        $lbl = $array;
        $p1->SetLabels($lbl);
        $p1->SetShadow();

        $graph->Add($p1);

        $graph->Stroke();
    }

}