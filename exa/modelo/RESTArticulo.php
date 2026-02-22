<?php

namespace exa\modelo;

use act0713\orm\entidad\Articulo;
use PDO;

class RESTArticulo
{

  public static string $TABLA = 'articulo';
  public static string $PK = 'referencia';

  protected $pdo;

  public function __construct()
  {
    $dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol13;charset=utf8mb4";
    $usuario = 'jrider';
    $clave = 'usuario';
    $opciones = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false
    ];
    $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);
  }

  public function getArticulos()
  {

    $sql = "SELECT * FROM articulo ";

    if (isset($_GET['descripcion'])) {
      $sql .= "WHERE descripcion LIKE :descripcion ";
      $descripcion = filter_input(INPUT_GET, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    $stmt = $this->pdo->prepare($sql);
    if (isset($descripcion)) $stmt->bindValue(':descripcion', "%$descripcion%");
    
    $stmt->execute();

    $datos = [];
    while ($fila = $stmt->fetch()) {
      $datos[] = new Articulo($fila);
    }

    return empty($datos) ? null : $datos;
  }
}
