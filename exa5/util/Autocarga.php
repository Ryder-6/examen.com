<?php

namespace exa5\util;

class Autocarga
{

  public static function iniAutocarga() : void
  {
    spl_autoload_register(self::class . "::autocarga");
  }

  public static function autocarga($clase)
  {
    try {

      $archivo = str_replace("\\", "/", $clase);
      if ($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php") {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php");
      } else {
        throw new \Exception("no se encuentra la clase $clase", 1);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      header('content-type: application/json; charset= utf-8');
      echo json_encode($e->getMessage());
      exit;
    }
  }
}
