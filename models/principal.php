<?php
require_once 'config.php';
require_once 'conexion.php';
class PrincipalModel{
    private $pdo, $con;
    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }
    //consultaTelemetriaMadurador($telemetria)

    public function consultaTelemetriaMadurador($telemetria){
        $valor =intval($telemetria);
        $consult = $this->pdo->prepare("SELECT nombre_contenedor ,descripcionC ,extra_1  FROM contenedores WHERE telemetria_id = ? and estado=1");
        $consult->execute([$valor]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }
    public function listaNestle()
    {
        $consult = $this->pdo->prepare("SELECT *  FROM control_dispositivos WHERE estado_control = 1");
        $consult->execute([]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>