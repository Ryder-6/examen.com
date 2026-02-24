<?php

namespace exa5\enrutador;

use Exception;

class Enrutador
{

  protected array $ruta;

  public function __construct()
  {
    $this->ruta['path'] = "#^/resena$#";
    $this->ruta['verbo'] = 'GET';
    $this->ruta['modelo'] = \exa5\modelo\RESTResena::class;
    $this->ruta['metodo'] = 'getResena';

    $this->iniPeticion();
  }

  private function iniPeticion()
  {

    try {
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $verbo = $_SERVER['REQUEST_METHOD'];

      if (!preg_match($this->ruta['path'], $path) || $verbo !== $this->ruta['verbo']) {
        throw new \Exception("la ruta $path o el verbo $verbo no admitidos", 6);
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
        throw new \Exception("El modelo $modelo o el metodo $metodo no admitido", 7);
      }

      $instancia = new $modelo();
      $datos = call_user_func_array([$instancia, $metodo],[]);
      $this->enviaResultado($datos);
    } catch (\Exception $e) {
      $this->enviaError($e);
    }
    catch (\PDOException $e) {
      $this->enviaError(new Exception($e->getMessage()));
    }
  }

  private function enviaResultado(?array $datos) {
    $resultado['datos'] = $datos;
    $resultado['error'] = null;
    http_response_code(200);
    header('content-type:application/json;charset=utf8');
    echo json_encode($resultado);
  }

  private function enviaError(\Exception $e) {
    $resultado['datos'] = null;
    $resultado['error'] = $e->getMessage();
    http_response_code(200);
    header('content-type:application/json;charset=utf8');
    echo json_encode($resultado);
  }
}
