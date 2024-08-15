<?php

//$telemetria_externa = $_POST['telemetria_externa'];
//$fecha_externa = $_POST['fecha_externa'];
$datosRecibidos = file_get_contents("php://input");
//file_put_contents($filename, (file_get_contents('php://input')));
//$data1 ="";
echo print_r($datosRecibidos);
$contenedor = json_decode($datosRecibidos);
 //echo print_r($contenedor[0]->fecha_pasada);
//file_put_contents('my_server_folder/'.$fecha_externa, base64_decode($datosRecibidos));
//$cer = $contenedor['fecha_pasada'];
//echo $cer;
//$cer1 = $contenedor['id'];
//echo $cer1;
echo 'my_server_folder/'.$contenedor[0]->telemetria_id.'_'.$contenedor[0]->fecha_pasada.'.png';
file_put_contents('my_server_folder/'.$contenedor[0]->telemetria_id.'_'.$contenedor[0]->fecha_pasada.'.png', base64_decode($contenedor[0]->img));
?>
