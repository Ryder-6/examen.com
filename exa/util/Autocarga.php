<?php

namespace exa\util;

class Autocarga
{

  public static function registraAutocarga()
  {
    spl_autoload_register(self::class . "::autocarga");
  }


  public static function autocarga($clase)
  {
    try {
      $archivo = str_replace("\\", "/", $clase);

      if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php")) {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php");
      } else {
        throw new \Exception("la definicion de la clase no existe", 5);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      header('content-type: application/json;charset=utf8');
      echo json_encode($e->getCode(), $e->getMessage());
    }
  }
}
