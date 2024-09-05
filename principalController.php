<?php
// necesarios del modelo

require_once 'models/principal.php';

#ini_set('display_errors', 1);

#ini_set('display_startup_errors', 1);

#error_reporting(E_ALL);

 
require_once 'models/principal.php';


require '../../test/ztotal/vendor/autoload.php';
//use Exception;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;
use MongoDB\BSON\UTCDateTime ;

$option = (empty($_GET['option'])) ? '' : $_GET['option'];
$principal = new PrincipalModel();
//opciones a trabajar
switch ($option) {


    case 'listaNestle':
        $idf = $_GET['id'];
        $datitos = $principal->listaNestle();

        echo json_encode($datitos);
    break;

    case 'datosContendores':
        $idf = $_GET['id'];
        $datitos = $principal->consultaTelemetriaMadurador($idf);

        echo json_encode($datitos);
    break;
 
    case 'consultaFechaMadurador1':

        $idf = $_GET['id'];
        $parte2 =  substr($idf, strpos($idf,',')+strlen(','));
        $fechaaInicio = substr($idf, 0, strpos($idf, ','));
        $fechaFin = substr($parte2, 0, strpos($parte2, ';'));
        $telemetria = substr($parte2, strpos($parte2,';')+strlen(';'));
            
        $uri = 'mongodb://localhost:27017';
        // Specify Stable API version 1
        $apiVersion = new ServerApi(ServerApi::V1);
        // Create a new client and connect to the server
        $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

        $datitos = $principal->consultaTelemetriaMadurador($telemetria);

         $fechaaInicio1 =$fechaaInicio.":00";
         $fechaFin1 =$fechaFin.":00";
         //problemas con fecha 5 horas menos debe ser UTC-5
         $puntoA = strtotime($fechaaInicio);
         $puntoA1 = strtotime("-5 hours",$puntoA)*1000;
         $puntoB = strtotime($fechaFin)  ;
         $puntoB1 = strtotime("-5 hours" ,$puntoB)*1000  ;
         // se selcciona los campos y las fechas 
         $cursor  = $client->ztrack_ja->madurador->find(array('$and' =>array( ['created_at'=>array('$gte'=>new MongoDB\BSON\UTCDateTime($puntoA1),'$lte'=>new MongoDB\BSON\UTCDateTime($puntoB1)),'telemetria_id'=>intval($telemetria)] )),
         array('projection' => array('_id' => 0,'trama'=> 1, 'created_at' => 1,'set_point' => 1,'temp_supply_1' => 1,'return_air' => 1,'evaporation_coil' => 1,'ambient_air' => 1,'relative_humidity' => 1,'power_state' => 1,
         'compress_coil_1' => 1,'consumption_ph_1' => 1,'consumption_ph_2' => 1,'consumption_ph_3' => 1,'line_voltage' => 1,'defrost_term_temp' => 1,'defrost_interval' => 1,'id'=>1 ,'power_kwh' =>1),'sort'=>array('id'=>1)));

         
         $total['fecha']= [];
         $total['setPoint'] =[];
         $total['returnAir'] =[];
         $total['tempSupply'] =[];
         $total['inyeccionEtileno'] =[];
         $total['madurador'] = [];
	 $total['humedity']=[];
         $total['contenedor'] = $datitos;

         foreach ($cursor as $document) {
            //array_push($total['fecha'],$document['created_at']);
            $fechaJa = json_decode($document['created_at'])/1000;
            // $fechaJa1 = $fechaJa['$date'];
            $fechaD = date('d-m-Y H:i:s', $fechaJa);
    
            $puntoA = strtotime($fechaD);
            $puntoA1 = strtotime("+5 hours",$puntoA);
            $fechaD1 = date('d-m-Y H:i:s', $puntoA1);
           // array_push($total,$fechaD);
           array_push($total['fecha'],$fechaD1);
            //array_push($total['tramaMadurador'],$document);  
            
            array_push($total['setPoint'],$document['set_point']);
            array_push($total['returnAir'],$document['return_air']);
            array_push($total['tempSupply'],$document['temp_supply_1']);
	    //$hum = (0 >= $document['relative_humidity']<= 99) ? $document['relative_humidity'] :null ; 
            //array_push($total['humedity'],$hum);
	if($document['relative_humidity']>=0 && $document['relative_humidity']<=99){
$hum =$document['relative_humidity'];
}else{
$hum =null;
}
            array_push($total['humedity'],$hum);


            if($document['power_state']==1.00 ){
                array_push($total['inyeccionEtileno'],0); 
            }else{
                array_push($total['inyeccionEtileno'],100);
            }
            array_unshift($total['madurador'],$document);         
        }
        echo json_encode($total);
        break;
        
    

    default:
        # code...
        break;
}
