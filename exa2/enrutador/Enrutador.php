<?php

namespace exa2\enrutador;

use exa2\entidad\Cliente;
use Exception;

class Enrutador
{
  protected array $ruta;

  public function __construct()
  {
    $this->ruta['path'] = "#^/cliente$#";
    $this->ruta['verbo'] = 'GET';
    $this->ruta['modelo'] = \exa2\modelo\RESTCliente::class;
    $this->ruta['metodo'] = 'getCliente';

    $this->iniPeticion();
  }

  private function iniPeticion()
  {

    try {
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $verbo = $_SERVER['REQUEST_METHOD'];

      if (
        !preg_match($this->ruta['path'], $path) ||
        $verbo !== $this->ruta['verbo']
      ) {
        throw new \Exception("el path $path o la peticion $verbo no son aceptadas", 3);
      }

      $this->procPeticion();
    } catch (\Exception $e) {
      $this->enviaError($e);
    }
  }

  private function procPeticion()
  {
    try {
      $modelo = $this->ruta['modelo'];
      $metodo = $this->ruta['metodo'];

      if (!class_exists($modelo) || !method_exists($modelo, $metodo)) {
        throw new \Exception("El modelo $modelo o el metodo $metodo no encontrado", 4);
        
      }

      $modelo = new $modelo;
      $datos = call_user_func_array([$modelo, $metodo], []);

      $this->enviaRespuesta($datos);
    } catch (\Exception $e) {
      $this->enviaError($e);
    }catch(\PDOException $e){
      $this->enviaError(new Exception($e->getMessage(), $e->getCode()));
    }

  }

  private function enviaRespuesta($datos)
  {
    $resultado['datos'] = $datos ?? null;
    $resultado['error'] = null;

    http_response_code(200);
    header('content-type: application/json; charset=utf8');
    echo json_encode($resultado);
  }

  private function enviaError(\Exception $e)
  {
    $resultado['error'] = true;
    $resultado['datos'] = $e->getMessage();
    http_response_code(500);
    header('content-type: application/json; charset=utf8');
    echo json_encode($resultado);
  }
}
