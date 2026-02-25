<?php

namespace exra56713\modelo;

use exra56713\entidad\Resena13;
use PDO;

class RESTResena13
{
  public string $TABLE = 'reseña';
  public string $PK = 'id_reseña';

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

  public function getResenas(): array
  {
    if (!isset($_GET['clasificacion'])) return [];

    $clasificacion = filter_input(INPUT_GET, 'clasificacion', FILTER_VALIDATE_INT, [
      'options' => [
        'min_range' => 0,//en mi bd hay una reseña de 0
        'max_range' => 5
      ]
    ]);

    if ($clasificacion === null || $clasificacion === false) return [];

    $sql = "SELECT * FROM {$this->TABLE} 
            WHERE clasificacion = :clasificacion";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":clasificacion", $clasificacion);

    $stmt->execute();

    $datos = [];
    while ($fila = $stmt->fetch()) {
      $datos[] = new Resena13($fila);
    }

    return empty($datos) ? [] : $datos;
  }
}
