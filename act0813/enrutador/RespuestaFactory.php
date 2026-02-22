<?php

namespace act0813\enrutador;

use act0813\error\ErrorServicio;



class RespuestaFactory
{

  private static function enviarJSON(array $resultado): void
  {
    http_response_code($resultado['codigo']);
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
  }

  public static function ok(mixed $datos): void
  {
    $resultado = [
      'exito' => true,
      'datos' => $datos,
      'codigo' => 200,
      'error' => null
    ];

    self::enviarJSON($resultado);
  }

  public static function created(mixed $datos): void
  {
    $resultado = [
      'exito' => true,
      'datos' => $datos,
      'codigo' => 201,
      'error' => null
    ];

    self::enviarJSON($resultado);
  }

  public static function noContent(): void
  {
    http_response_code(204);
    exit;
  }

  public static function error(ErrorServicio $es): void
  {

    $resultado = [
      'exito' => false,
      'datos' => null,
      'codigo' => $es->getEstado(),
      'codigoError' => $es->getCodigoError(),
      'error' => $es->getMensajeError()
    ];

    self::enviarJSON($resultado);
  }
}
