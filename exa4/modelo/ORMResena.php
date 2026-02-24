<?php

namespace exa4\modelo;

use PDO;
use exa4\entidad\Resena;

class ORMResena
{

  public string $TABLA = 'reseña';
  public string $PK = 'id_reseña';

  protected PDO $pdo;

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

  public function getResenas(string $referencia): array
  {
    $sql = "SELECT * FROM reseña 
            WHERE referencia = :referencia";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":referencia", $referencia);

    $datos = [];
    while ($fila = $stmt->fetch()) {
      $referencia = new Resena($fila);
    }

    return $datos;
  }
}
