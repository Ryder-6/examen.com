<?php

namespace exa1\enrutador;

use Exception;

class Enrutador
{
  protected array $ruta;

  public function __construct()
  {
    $this->ruta['path'] = "#^/cliente$#";
    $this->ruta['verbo'] = "GET";
    $this->ruta['modelo'] = \exa1\modelo\RESTCliente::class;
    $this->ruta['metodo'] = 'getCliente';

    $this->idenPeticion();
  }

  private function idenPeticion()
  {

    try {
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $verbo = $_SERVER['REQUEST_METHOD'];

      if (preg_match( $this->ruta['path'], $path) &&
        $verbo === $this->ruta['verbo']) {
        $this->procPeticion();
      } else throw new \Exception("La ruta o el metodo no son validos", 2);
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
        throw new \Exception("La clase $modelo o el metodo $metodo no valido", 3);
      }

      $modelo = new $modelo;

      $datos = call_user_func_array([$modelo, $metodo], []);

      $this->enviaResultado($datos);
    } catch (\Exception $e) {
      $this->enviaError($e);
    } catch (\PDOException $e) {
      $this->enviaError(new \Exception($e->getMessage(), $e->getCode()));
    }
  }
  private function enviaResultado($datos)
  {
    $resultado['datos'] = $datos ?? [];
    $resultado['error'] = null;

    http_response_code(200);
    header('content-type: application/json;charset=utf8');
    echo json_encode($resultado);
  }

  private function enviaError(\Exception $e)
  {
    $resultado['datos'] = [];
    $resultado['error'] = $e->getMessage();

    http_response_code($e->getCode());
    header('content-type: application/json; charset=utf8');

    echo json_encode($resultado);
  }
}
