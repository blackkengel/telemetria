<?php
ini_set('max_execution_time', 999999);
ini_set("memory_limit", "999999M");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Expose-Header: Content-Disposition");
date_default_timezone_set('America/Mexico_City');


class getTocken {
    private $directorio     = __DIR__ . '/data/token/cliente/';
    private $directorioKey  = __DIR__ . '/data/keys/cliente/';
    private $clientes       = [45987];
    private $url            = "https://api.service24gps.com/api/v1/gettoken";


	function validaToken(){
        print_r("se inicia servicio");
            $this->validateDir($this->directorio);
            foreach($this->clientes as $cliente){
                //// se valida si existe la apiKey del cliente
                $apiKeyData=$this->getApiKey($cliente);
                if(!$apiKeyData){
                    continue;
                }
                $fileSystem=$this->directorio.$cliente.".json";

                if (!file_exists($fileSystem)) {
                    $file = fopen($fileSystem, "w");
                    if ($file) {
                        print_r("El archivo fue creado exitosamente.");
                        fclose($file);
                        //se actualiza el archivo 
                        $dataToken=$this->getNewToken(($apiKeyData));
                        $dataSaveToken=[
                            "date"=>date("Y-m-d H:i:s"),
                            "token"=>$dataToken
                        ];
                        file_put_contents($fileSystem,json_encode($dataSaveToken));
                    } else {
                        print_r("Hubo un error al intentar crear el archivo.");
                    }
                }else{
                    // verifica ultima actualizacion de tocken
                    $dataToken=json_decode(file_get_contents($fileSystem));
                    $update=$this->validateDate($dataToken->date);
                    

                    if($update){
                        print_r("Ya pasaron mas de 5 horas, se actualiza el token.");
                        $dataToken=$this->getNewToken(($apiKeyData));
                        $dataSaveToken=[
                            "date"=>date("Y-m-d H:i:s"),
                            "token"=>$dataToken
                        ];
                        file_put_contents($fileSystem,json_encode($dataSaveToken));
                    }else{
                        print_r("Aun no pasan mas de 5 horas, No se actualiza el token.");
                    }
                }
            } 
    }


    function getApiKey($idCliente){
        $this->validateDir($this->directorioKey);
        $fileSystemKey=$this->directorioKey.$idCliente.".json";
        if (!file_exists($fileSystemKey)) {
            return false;
        }else{
            $dataKey=file_get_contents($this->directorioKey.$idCliente.".json");
            return $dataKey;
        }
    }

    function validateDate($date){
        $dateToken = new DateTime($date);
        $fechaHoraActual = new DateTime();

        $fechaHoraActual->modify('-5 hours');
        if ($dateToken < $fechaHoraActual) {
            return true;
        } else {
            return false;
        }
    }

    function validateDir($directorio){
        if (!is_dir($directorio)) {
            if (mkdir($directorio, 0777, true)) {
                print_r("La carpeta fue creada exitosamente.");
                return true;
            } else {
                print_r("Hubo un error al intentar crear la carpeta.");
                return false;
            }
        }
        return true;

    }

    function getNewToken($dataJson){
        $dataJson=json_decode($dataJson);
        $data = array(
            'apikey' => $dataJson->apiKey,
            'token' => "",
            'username' => $dataJson->username,
            'password' => $dataJson->password,
        );
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $respuesta = curl_exec($ch);
        if(curl_errno($ch)) {
            echo 'Error en cURL: ' . curl_error($ch);
        } else {
            $resultado = json_decode($respuesta, true);
        }
        curl_close($ch);
        return $resultado["data"];
    }
}

$dataToken = new getTocken();
$dataToken->validaToken();

?> 