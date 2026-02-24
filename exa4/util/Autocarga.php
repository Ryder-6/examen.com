<?php

namespace exa4\util;

class Autocarga
{

  public static function iniAutocarga() {
    spl_autoload_register(self::class . "::autocarga");
  }

  public static function autocarga($clase) {

  try {
    $archivo = str_replace("\\", "/", $clase);
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php")) {
      require_once($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php");
    }
    else {
      throw new \Exception("Error, autocarga no encontrada", 1);
      
    }
  } catch (\Exception $e) {
    http_response_code(500);
    header("content-type:application/json;charset=utf-8");
    echo json_decode($e->getMessage());
  }

    
  }
}
