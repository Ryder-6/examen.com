<?php

namespace exa1\util;

class Autocarga
{

  public static function iniciaAutocarga()
  {
    spl_autoload_register(self::class . "::autocarga");
  }

  public static function autocarga($clase)
  {

    try {
      $archivo = str_replace('\\', '/', $clase);

      if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php")) {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php");
      } else {
        throw new \Exception("la clase $clase no se ha encontrado", 1);
      }
      //code...
    } catch (\Exception $e) {
      http_response_code(500);
      header("content-type: application/json;charset=utf8");
      echo json_encode([$e->getMessage(), $e->getCode()]);
    }
  }
}
