<?php

namespace exa07\enrutador;

class Enrutador
{

  private array $ruta;

  public function __construct()
  {
    $this->ruta['path'] = "#^/cliente$#";
    $this->ruta['verbo'] = 'GET';
    $this->ruta['modelo'] = \exa07\modelo\RESTCliente::class;
    $this->ruta['metodo'] = "getCliente";

    $this->idenPeticion();
  }

  private function idenPeticion()
  {
    try {
      $metodoHTTP = $_SERVER['REQUEST_METHOD'];
      $pathPeticion = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

      if (
        $metodoHTTP === $this->ruta['verbo'] &&
        preg_match($this->ruta['path'], $pathPeticion)
      ) {
        $this->procPeticion();
      } else {
        throw new \Exception("La peticion no valida", 404);
      }
    } catch (\Exception $e) {
      $this->enviaError($e);
    }
  }

  private function procPeticion()
  {
    try {
      $claseModelo = $this->ruta['modelo'];
      $metodo = $this->ruta['metodo'];

      if (!class_exists($claseModelo) || !method_exists($claseModelo, $metodo)) {
        throw new \Exception("Clase o peticion no existen", 404);
      }

      $modelo = new $claseModelo();
      $datos = call_user_func_array([$modelo, $metodo], []);

      $this->enviaRespuesta($datos);
    } catch (\Exception $e) {
      $this->enviaError($e);
    } catch (\PDOException $e) {
      $error = $e->getCode();
      switch ($error) {
        case '2300':
          $this->enviaError(new \Exception($e->getMessage(), 409));
          break;

        default:
          $this->enviaError(new \Exception($e->getMessage(), 500));

          break;
      }
    }
  }

  private function enviaRespuesta($datos)
  {
    $resultado['error'] = null;
    $resultado['datos'] = $datos ?? [];

    http_response_code(200);
    header("Content-type: application/json; charset=utf-8");

    echo json_encode($resultado);
    exit;
  }

  private function enviaError(\Exception $e)
  {
    $resultado['error'] = $e->getMessage();
    $resultado['datos'] = null;

    http_response_code($e->getCode());
    header("Content-type: application/json; charset=utf-8");

    echo json_encode($resultado);
    exit;
  }
}
