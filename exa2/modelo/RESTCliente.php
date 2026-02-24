<?php
namespace exa2\modelo;

use exa2\entidad\Cliente;
use PDO;

class RESTCliente{

  public string $TABLA = 'cliente';
  public string $PK = 'nif';

  protected $pdo;

  public function __construct()
  {
    $dsn = 'mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol13;charset=utf8mb4';
    $usuario = 'jrider';
    $clave = 'usuario';
    $opciones = [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false
    ];
    $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);

    $this->getCliente();
  }

  public function getCliente() :array{

  //si no hay criterio de busqueda no pasa nigun cliente
    
    $sql = 'SELECT * FROM cliente ';

    if (!isset($_GET['nif'])) return [];

    
    $sql.= "WHERE nif = :nif";
    $nif = filter_input(INPUT_GET,'nif', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $stmt = $this->pdo->prepare($sql);

    $stmt->bindValue(':nif', $nif);

    $stmt->execute();

    $datos = [];
    while ($fila = $stmt->fetch()) {       


      $datos[] = new Cliente($fila);
    }

    return empty($datos) ? null : $datos;
  }

}


?>