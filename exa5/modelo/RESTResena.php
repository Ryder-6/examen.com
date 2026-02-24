<?php

namespace exa5\modelo;

use exa5\entidad\Resena;
use PDO;

class RESTResena
{
  public string $TABLA = 'reseña';
  public string $PK = 'id_reseña';

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
    $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);
  }

  public function getResena()
  {

    $sql = "SELECT * FROM reseña 
            WHERE referencia = :referencia ";
    if (!$_GET['referencia']) return [];
    $referencia = filter_input(INPUT_GET, 'referencia', FILTER_SANITIZE_SPECIAL_CHARS);
    $stmt = $this->pdo->prepare($sql);

    $stmt->bindValue(":referencia", $referencia);

    $stmt->execute();

    $datos = [];
    while ($fila = $stmt->fetch()) {
      $datos[] = new Resena($fila);
    }
    return empty($datos) ? [] : $datos;
  }
}
