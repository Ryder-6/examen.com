<?php

namespace exra56713\enrutador;

use Exception;

class Enrutador13
{

  protected array $ruta;
  public function __construct()
  {
    $this->ruta['path'] = "#^/resenas$#";
    $this->ruta['verbo'] = 'GET';
    $this->ruta['modelo'] = \exra56713\modelo\RESTResena13::class;
    $this->ruta['metodo'] = 'getResenas';

    $this->valPeticion();
  }

  private function valPeticion()
  {
    try {
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $verbo = $_SERVER['REQUEST_METHOD'];
      if (!preg_match($this->ruta['path'], $path) || $verbo !== $this->ruta['verbo']) {
        throw new \Exception("Error, path $path o verbo $verbo invalido", 404);
      }

      $this->ejecMetodo();
    } catch (\Exception $e) {
      $this->enviaError($e);
    }
  }

  private function ejecMetodo() {
  try {
    $modelo = $this->ruta['modelo'];
    $metodo = $this->ruta['metodo'];
    
    if (!class_exists($modelo) || !method_exists($modelo, $metodo)) {
    throw new \Exception("Error, modelo $modelo o metodo $metodo no encontrado", 402);
    }
    $modeloIni = new $modelo();

    $datos = call_user_func_array([$modeloIni, $metodo],[]);

    $this->enviaResultado($datos);

  } catch (\Exception $e) {
    $this->enviaError($e);
  } catch (\PDOException $e) {
    $this->enviaError(new Exception($e->getMessage(), $e->getCode()));
  }
  }

  private function enviaResultado(array $datos) {
    $resultado['error'] = null;
    $resultado['datos'] = $datos;

    header('content-type:application/json;charset=utf-8');
    http_response_code(200);
    echo json_encode($resultado);
  
  }
  private function enviaError(\Exception $e) {
    $resultado['error'] = $e->getMessage();
    $resultado['datos'] = null;

    header('content-type:application/json;charset=utf-8');
    http_response_code(500);
    echo json_encode($resultado);
  }
}
