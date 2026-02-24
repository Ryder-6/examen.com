<?php
namespace exa3\util;

class Autocarga 
{
  public static function iniAutocarga()  {
    spl_autoload_register(self::class . "::autocarga");
  }

  public static function autocarga($clase) {
    try {
    $archivo = str_replace("\\", '/', $clase);
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php")) {
      require_once($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php");
    }else {
      throw new \Exception("la clase $clase no encontrada", 1);
    }
      
    } catch (\Exception $e) {
      header('content-type:application/json;charset=utf8');
      http_response_code(500);
      echo json_encode($e->getMessage(), $e->getCode());
    }
    
  }
}



?>