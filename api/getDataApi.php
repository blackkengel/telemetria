<?php
ini_set('max_execution_time', 999999);
ini_set("memory_limit", "999999M");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Expose-Header: Content-Disposition");
date_default_timezone_set('America/Mexico_City');



class getDataApi {
    private $directorio  = __DIR__ . '/data/token/cliente/';
    private $directorioKey  = __DIR__ . '/data/keys/cliente/';
    private $idCliente   = 0;
    private $token       = "";
    private $apiKey      = "";
    private $urlPost     = "https://api.service24gps.com/api/v1/";

	function __construct($idCliente) {
        $this->idCliente = $idCliente;
        $this->initData();
	}

	function initData(){
        $fileSystem=$this->directorio.$this->idCliente.".json";
        if (is_dir($this->directorio) && file_exists($fileSystem)) {
            $dataToken=json_decode(file_get_contents($fileSystem));
            $this->token=$dataToken->token;
        }else{
            $data = [
                'success' => false,
                'message'=> "No hay fileSystem del cliente:".$this->idCliente
            ];
            http_response_code(200);
            echo json_encode($data);
        }  
        $fileSystemApi=$this->directorioKey.$this->idCliente.".json";
        if (is_dir($this->directorioKey) && file_exists($fileSystemApi)) {
            $dataApi=json_decode(file_get_contents($fileSystemApi));
            $this->apiKey=$dataApi->apiKey;
        }else{
            $data = [
                'success' => false,
                'message'=> "No hay fileSystem del cliente:".$this->idCliente
            ];
            http_response_code(200);
            echo json_encode($data);
        }
    }

    function getDataList(){
        $metodo="vehicleGetAllComplete";
        $data=array(
            "apikey"=>$this->apiKey,
            "token"=>$this->token
        );
        $listado = $this->curlPost($this->urlPost.$metodo,$data);
        $data = [
            'success' => true,
            'activos'=> $listado,
        ];
        echo json_encode($data);
    }

    function curlPost($url,$data){

        $ch = curl_init($url);
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

?> 