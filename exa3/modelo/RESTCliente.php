<?php
namespace exa3\modelo;

use act0713\orm\entidad\Cliente;
use PDO;

class RESTCliente{
  public string $TABLA = 'cliente';
  public string $PK = 'nif';
  
  protected $pdo;

  public function __construct()
  {
    $dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol13;charset=utf8mb4";
    $usuario = 'jrider';
    $clave = 'usuario';
    $opciones = [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    $this->pdo = new PDO($dsn, $usuario,$clave, $opciones);

  }

  public function postCliente() : bool {
    $datos = is_array($_POST) ? $_POST : [];

    $permitidos = ['nif','nombre','apellidos','clave','iban','telefono','email','ventas'];
    $fila = [];
    foreach ($permitidos as $campo) {
      if (isset($datos[$campo])) $fila[$campo] = $datos[$campo];
    }

    // Campos obligatorios
    $obligatorios = ['nif','nombre','apellidos','clave'];
    foreach ($obligatorios as $req) {
      if (empty($fila[$req])) return false;
    }

    // Preparar SQL dinámico
    $columnas = array_keys($fila);
    $placeholders = array_map(fn($c) => ':' . $c, $columnas);

    $sql = "INSERT INTO {$this->TABLA} (" . implode(', ', $columnas) . ") VALUES (" . implode(', ', $placeholders) . ")";
    $stmt = $this->pdo->prepare($sql);
    foreach ($fila as $col => $val) {
      // tipos simples: ventas -> float
      if ($col === 'ventas') $val = (float)$val;
      $stmt->bindValue(':' . $col, $val);
    }

    return $stmt->execute();
  }
}


?>