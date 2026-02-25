<?php

namespace exra56713\util;

class Autocarga13
{

  public static function iniAutocarga()
  {
    spl_autoload_register(self::class . "::autocarga");
  }
  public static function autocarga($clase) {
  try {
    
    $archivo = str_replace("\\", "/", $clase);
  
      if (file_exists($_SERVER['DOCUMENT_ROOT'] ."/$archivo.php")) {
        require_once($_SERVER['DOCUMENT_ROOT'] ."/$archivo.php");
      }else {
        throw new \Exception("El archivo $archivo no encontrado", 1);
      }
  } catch (\Exception $e) {
    header("content-type: application/json;charset=utf-8");
  }  
  }
}
