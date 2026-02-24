<?php
namespace exa1\modelo;

use act0713\orm\entidad\Cliente;
use PDO;

class RESTCliente{
  public static $TABLA = 'cliente';
  public static $PK = 'nif';

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

  public function getCliente()  {
  // buscar por nombre o todos los clientes
    $sql = "SELECT * FROM cliente ";

    if (isset($_GET['nombre'])) {
      $sql.= "WHERE nombre LIKE :nombre";
      $nombre = filter_input(INPUT_GET, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    $stmt = $this->pdo->prepare($sql);
    if (isset($nombre)) {
      $stmt->bindvalue(':nombre', "%$nombre%");
    }

    $stmt->execute();

    $datos = [];
    while ($fila = $stmt->fetch()) {
      $datos[] = new Cliente($fila);
    }

    return empty($datos) ? null : $datos;


  }
}


?>