<?php
ini_set('max_execution_time', 999999);
ini_set("memory_limit", "999999M");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Expose-Header: Content-Disposition");
date_default_timezone_set('America/Mexico_City');
include_once("getDataApi.php");


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
$dataApi = new getDataApi($idCliente);
$dataApi->getDataList();

?> 