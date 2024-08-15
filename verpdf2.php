<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');
//ini_set('error_reporting',1);//para xamp
//require('dbconex.php');
//define ('FPDF_FONTPATH', '. /');
// initiate FPDI

$datosRecibidos = file_get_contents("php://input");
$contenedor = json_decode($datosRecibidos);

$telemetria_id=$contenedor[0]->telemetria_id;
$dispositivo=$contenedor[0]->dispositivo;
$empresa=$contenedor[0]->empresa;
$rango=$contenedor[0]->rango;

$fecha1 = date_create('2023-11-05');
$fecha2 =  date_create('2023-11-11');


//$archivo='oli';

$pdf = new \setasign\Fpdi\Fpdi();

$pdf->SetTopMargin(25);
$pdf->SetAutoPageBreak(true,30);  
$pdf->AddFont('Flama-Basic','','Flama-Basic.php');
$pdf->AddFont('Flama-Bold','','Flama-Bold.php');
#Establecemos los márgenes izquierda, arriba y derecha:
//importante agregar fuente
//$pdf->SetFont('Flama-Bold','',23);
//$pdf->SetTextColor(35,44,76);

//contamos el array
$contadoDatos = count($rango);

for ($i = 1; $i <= $contadoDatos; $i++) {
    if($i%2==0){
		$pdf->SetFont('Flama-Bold','',14);
		$pdf->SetTextColor(35,44,76);
		$pdf->Ln(115); 
		$pdf->Cell(180,20,utf8_decode($rango[$i-1]),0,0,'L');
		$datazo = 'my_server_folder/'.$telemetria_id.'_'.$rango[$i-1].'.png';
		$pdf->Image($datazo,15,160,170);
		$pdf->AliasNbPages();
	}else{
		$pdf->SetFont('Flama-Bold','',23);
        $pdf->SetTextColor(35,44,76);
		$pdf->AddPage();
		$pdf->Cell(180,20,utf8_decode($dispositivo." -  ".$empresa),0,0,'C');
		$pdf->SetFont('Flama-Bold','',14);
		$pdf->SetTextColor(35,44,76);
		$pdf->Ln(10); 
		//$pdf->Cell(180,20,utf8_decode($rango[0]),0,0,'L');
		$pdf->Cell(180,20,utf8_decode($rango[$i-1]),0,0,'L');
		$datazo = 'my_server_folder/'.$telemetria_id.'_'.$rango[$i-1].'.png';
		$pdf->Image($datazo,15,45,170);

	}
}

/*
$pdf->AddPage();
$pdf->Cell(180,20,utf8_decode($dispositivo." -  ".$empresa),0,0,'C');
$pdf->SetFont('Flama-Bold','',14);
$pdf->SetTextColor(35,44,76);
$pdf->Ln(10); 
$pdf->Cell(180,20,utf8_decode($rango[0]),0,0,'L');
$pdf->Image('my_server_folder/386_2023-11-20.png',15,45,170);
$pdf->Ln(115); 
$pdf->Cell(180,20,utf8_decode($rango[1]),0,0,'L');
$pdf->Image('my_server_folder/386_2023-11-21.png',15,160,170);
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->Cell(180,20,utf8_decode($dispositivo." -  ".$empresa),0,0,'C');
$pdf->SetFont('Flama-Bold','',14);
$pdf->SetTextColor(35,44,76);
$pdf->Ln(10); 
$pdf->Cell(180,20,utf8_decode($rango[2]),0,0,'L');
$pdf->Image('my_server_folder/386_2023-11-20.png',15,45,170);
$pdf->Ln(115); 
$pdf->Cell(180,20,utf8_decode($rango[3]),0,0,'L');
$pdf->Image('my_server_folder/386_2023-11-21.png',15,160,170);
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->Cell(180,20,utf8_decode($dispositivo." -  ".$empresa),0,0,'C');
$pdf->SetFont('Flama-Bold','',14);
$pdf->SetTextColor(35,44,76);
$pdf->Ln(10); 
$pdf->Cell(180,20,utf8_decode($rango[4]),0,0,'L');
$pdf->Image('my_server_folder/386_2023-11-20.png',15,45,170);
$pdf->Ln(115); 
$pdf->Cell(180,20,utf8_decode($rango[5]),0,0,'L');
$pdf->Image('my_server_folder/386_2023-11-21.png',15,160,170);
$pdf->AliasNbPages();


$pdf->AddPage();
$pdf->Cell(180,20,utf8_decode($dispositivo." -  ".$empresa),0,0,'C');
$pdf->SetFont('Flama-Bold','',14);
$pdf->SetTextColor(35,44,76);
$pdf->Ln(10); 
$pdf->Cell(180,20,utf8_decode($rango[6]),0,0,'L');
$pdf->Image('my_server_folder/386_2023-11-20.png',15,45,170);

*/

$pdf->Output('reportes/'.$dispositivo.'_'.$rango[0].'_'.$rango[$contadoDatos-1].'.pdf', 'F');
