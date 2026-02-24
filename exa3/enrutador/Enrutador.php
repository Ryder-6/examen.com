<?php

namespace exa3\enrutador;

use Exception;

class Enrutador
{
  private array $ruta;

  public function __construct()
  {
    $this->ruta['path'] = "#^/cliente$#";
    $this->ruta['verbo'] = 'POST';
    $this->ruta['modelo'] = \exa3\modelo\RESTCliente::class;
    $this->ruta['metodo'] = 'postCliente';

    $this->iniPeticion();
  }

  private function iniPeticion()
  {
    try {
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $verbo = $_SERVER['REQUEST_METHOD'];

      if (!preg_match($this->ruta['path'], $path) ||
        $this->ruta['verbo'] !== $verbo) {
        throw new \Exception("Error, el path $path o el $verbo no son validos", 2);
      }

      $this->procPeticion();

    } catch (\Exception $e) {
      $this->enviaError($e);
    }
  }

  private function procPeticion() {
  try {
    $modelo = $this->ruta['modelo'];
    $metodo = $this->ruta['metodo'];

    if (!class_exists($modelo) || !method_exists($modelo, $metodo)) {
      throw new \Exception("Error, modelo $modelo o metodo $metodo no encontrado ", 4);      
    }

    $modelo = new $modelo;
    
    $datos = call_user_func_array([$modelo, $metodo],[]);

    $this->enviaRespuesta($datos);

  } catch (\Exception $e) {
    $this->enviaError($e);
  } catch(\PDOException $e){}
    $this->enviaError(new Exception($e->getMessage(), $e->getCode()));
  }

  private function enviaRespuesta($datos) {
    $respuesta['error'] = null;
    $respuesta['datos'] = $datos;

    http_response_code(200);
    header('content-type: application/json; charset=utf8');
    echo json_encode($respuesta);


  }
  private function enviaError(\Exception $e) {
    $respuesta['error'] = $e->getMessage();
    $respuesta['datos'] = null;

    http_response_code(500);
    header('content-type: application/json;charset=utf8');
    echo json_encode($respuesta);
  }
}
