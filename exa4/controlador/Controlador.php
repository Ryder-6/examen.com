<?php

namespace exa4\controlador;

use act0813\enrutador\RespuestaFactory;

class Controlador
{

  private const string NS_MODELO = "\\exa4\\modelo\\";
  protected array $peticion;

  public function __construct()
  {
    try {

      $this->validaPeticion();
      list($clase, $metodo) = $this->validaMetodo();
      $param = isset($this->peticion['parameters']) ? $this->peticion['parameters'] : [];
      $datos = $this->ejecutaMetodo($clase, $metodo, $param);
      $this->enviaRespuesta($datos);
    } catch (\Exception $e) {
      $this->enviaError($e);
    }
  }

  private function validaPeticion()
  {
    $input = file_get_contents("php://input");
    $peticion = json_decode($json = $input, $associative = true, $flags = JSON_THROW_ON_ERROR);

    if (
      !isset($peticion['jsonrpc'], $peticion['method']) ||
      $peticion['jsonrpc'] !== '2.0'
    ) {
      throw new \Exception("Invalid Request", -32600);
    }

    $this->peticion = $peticion;
  }

  private function validaMetodo() {
    $metodo = explode(".", $this->peticion['method']);
    if (count($metodo) !== 2) {
      throw new \Exception("internal error", -32606);
    }

    $claseModelo = self::NS_MODELO . $metodo[0];
    $metodoModelo = $metodo[1];

    if (!class_exists($claseModelo) || !method_exists($claseModelo, $metodoModelo)) {
      throw new \Exception("Method not exists", -32601);
    }
    return [$claseModelo, $metodoModelo];
  }

   private function ejecutaMetodo(string $clase, string $metodo, array $parametros): array {
    $modelo = new $clase();
    $resultado = call_user_func_array([$modelo, $metodo], $parametros);
    return $resultado;
  }


  private function enviaRespuesta($datos) {
    $respuesta = ['jsonrpc' => '2.0', 'id' => $this->peticion['id'] ?? null];
    $respuesta['result'] = $datos;

    header('content-type: application/json; charset=utf8');
    echo json_encode($respuesta);
  }
  private function enviaError(\Exception $e) {
    $respuesta = ['jsonrpc' => '2.0', 'id' => $this->peticion['id'] ?? null];
    if ($e instanceof \PDOException) {
      $respuesta['error'];
    }
  }
}
