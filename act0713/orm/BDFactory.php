<?php
namespace act0713\orm;

class BDFactory {
  public static function create(): \PDO {
    $conf13 = require("BDconfig.php");
    $pdo = new \PDO($conf13['dsn'],
                    $conf13['usuario'],
                    $conf13['clave'],
                    $conf13['opciones']);
    return $pdo;
  }
}
?>