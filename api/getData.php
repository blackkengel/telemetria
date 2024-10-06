<?php
ini_set('max_execution_time', 999999);
ini_set("memory_limit", "999999M");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Expose-Header: Content-Disposition");
date_default_timezone_set('America/Mexico_City');
include_once("getDataApi.php");

class getDataMaster {
    private $directorio  = __DIR__ . '/cache/cliente/';
    private $idCliente   = 0;
    private $dataActivos = [];

	function __construct($idCliente) {
        $this->idCliente = $idCliente;
        $this->directorio .= $idCliente."/";
	}

	function getNewData($devices){
        if (is_dir($this->directorio)) {
            $archivos = scandir($this->directorio);
            foreach ($archivos as $archivo) {
                if ($archivo != "." && $archivo != "..") {
                    $nameFile=explode(".",$archivo);
                    $this->dataActivos[$nameFile[0]]=json_decode(file_get_contents($this->directorio.$archivo));
                }
            }
            $data = [
                'success' => true,
                'activos' => $this->dataActivos,
                'message' => ""
            ];
            // http_response_code(200);
            echo json_encode($data);
        }else{
            /// se trae toda la data de fyleSystem
            $data = [
                'success' => false,
                'message'=> "No hay fileSystem del cliente:".$this->idCliente
            ];
            // http_response_code(200);
            echo json_encode($data);
        }  
    }
    function deleteFyleSystem($device){
        unlink($this->directorio.$device.".json");
        $data = [
            'success' => true,
            'message' => "Se elimino FileSystem de ".$device,
        ];
        // http_response_code(200);
        echo json_encode($data);

    }

    function getDetailVehicle($device){
        if (is_dir($this->directorio)) {
            $dataActivo=json_decode(file_get_contents($this->directorio.$device.".json"));
            $data = [
                'success' => true,
                'activo' => $dataActivo,
                'message' => ""
            ];
            // http_response_code(200);
            echo json_encode($data);
        }else{
            $data = [
                'success' => false,
                'message'=> "No hay fileSystem del cliente:".$this->idCliente
            ];
            // http_response_code(200);
            echo json_encode($data);
        }  
    }
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // http_response_code(200);
    $data = [
        'success' => false,
        'message'=> "Método no permitido. Se requiere POST."
    ];
    echo json_encode($data);
}
$dataInput = json_decode(file_get_contents("php://input"));
if (!isset($dataInput->idCliente)){
    // http_response_code(200);
    $data = [
        'success' => false,
        'message'=> "Datos inválidos. falta dato idCliente"
    ];
    echo json_encode($data);
    exit;
}
$idCliente = $dataInput->idCliente;
$dataMaster = new getDataMaster($idCliente);
if($dataInput->metodo=="deleteFyleSystem"){
    $dataMaster->deleteFyleSystem($dataInput->activo);
}else if($dataInput->metodo=="getDetailVehicle"){
    $dataMaster->getDetailVehicle($dataInput->activo);
}else{
    $dataMaster->getNewData($dataInput->devices);
}

?> 