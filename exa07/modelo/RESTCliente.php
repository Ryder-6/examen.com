<?php

namespace exa07\modelo;

use exa07\entidad\Cliente;
use \PDO;

class RESTCliente
{
  public static string $TABLA = "cliente";
  public static string $PK = "nif";

  protected $pdo;

  public function __construct()
  {
    $dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol13;charset=utf8mb4";
    $usuario = 'jrider';
    $clave = 'usuario';
    $opciones = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);
  }

  public function getCliente()
  {
  /* 
    solo acepta 1 nif, 
    si no hay Get['nif'] falla
    si hay Get['nif'], pero no coincide, devuelve nada
  */
  if (!isset($_GET['nif'])) {
      throw new \Exception("Error, no hay identificador de cliente", 358);
    }
    $sql = "SELECT * FROM cliente
            WHERE nif = :nif";
    $nif = filter_input(INPUT_GET, 'nif', FILTER_SANITIZE_SPECIAL_CHARS);

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":nif", $nif);
    $stmt->execute();

    $datos = [];
    while ($fila = $stmt->fetch()) {      
      $datos[] = new Cliente($fila);
    }

    return empty($datos) ? null : $datos;
    
  }

  public function getAll()
  {
    $sql = "SELECT * FROM cliente";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $datos = [];
    while ($fila = $stmt->fetch()) {
      $datos[] = new Cliente($fila);
    }

    return empty($datos) ? null : $datos;
  }
}
