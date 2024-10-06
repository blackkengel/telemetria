<?php
ini_set('max_execution_time', 999999);
ini_set("memory_limit", "999999M");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Expose-Header: Content-Disposition");
date_default_timezone_set('America/Mexico_City');

$directorio = __DIR__ . '/cache/cliente/'; // Ruta de la carpeta

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // http_response_code(405);
    echo json_encode(["mensaje" => "Método no permitido. Se requiere POST."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (is_null($input)) {
    // http_response_code(400);
    echo json_encode(["mensaje" => "Datos inválidos. Asegúrese de enviar JSON."]);
    exit;
}


if (!isset($input['idclient'])){
	// http_response_code(400);
    echo json_encode(["mensaje" => "Datos inválidos. fata dato idclient."]);
    exit;
}
/// procesamos los datos para cracion de fileSystem
$directorio.=$input['idclient'];
$fileSystem=$directorio."/".$input['device'].".json";
if (!is_dir($directorio)) {
    if (mkdir($directorio, 0775, true)) {
        print_r("La carpeta fue creada exitosamente.");

    } else {
        print_r("Hubo un error al intentar crear la carpeta.");
    }
}

if (!file_exists($fileSystem)) {
    $file = fopen($fileSystem, "w");
    if ($file) {
        print_r("El archivo fue creado exitosamente.");
        fclose($file);
    } else {
        print_r("Hubo un error al intentar crear el archivo.");
    }
}


$input['latitude']  = formatearFlotante($input['latitude']);
$input['longitude'] = formatearFlotante($input['longitude']);
file_put_contents($fileSystem,json_encode($input));
$response = [
    "mensaje" => "Datos recibidos correctamente",
];

// http_response_code(200);
echo json_encode($response);

function formatearFlotante($numero) {
    $partes = explode('.', $numero);
    if (isset($partes[1]) && strlen($partes[1]) > 6) {
        return number_format($numero, 6, '.', '');
    } else {
        return $numero;
    }
}
?>