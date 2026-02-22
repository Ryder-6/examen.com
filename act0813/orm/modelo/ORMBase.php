<?php

namespace act0813\orm\modelo;

use act0813\orm\entidad\Entidad;

abstract class ORMBase
{
  protected string $tabla;
  protected string $clavePrimaria;
  protected \PDO $cbd;

  public abstract function getClaseEntidad(): string;

  public function __construct(\PDO $pdo)
  {
    $this->cbd = $pdo;
  }

  public function getAll(): array
  {
    $sql = "SELECT * FROM {$this->tabla}";
    $stmt = $this->cbd->prepare($sql);
    $stmt->execute();
    $filas = [];
    while ($fila = $stmt->fetch()) {
      $clase = $this->getClaseEntidad();
      $filas[] = new $clase($fila);
    }
    return $filas;
  }

  public function get(mixed $id): ?Entidad
  {
    $sql = "SELECT * FROM {$this->tabla} ";
    $sql .= "WHERE {$this->clavePrimaria} = :id";

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(':id', $id);

    $stmt->execute();
    $fila = $stmt->fetch();
    $clase = $this->getClaseEntidad();
    return $fila ? new $clase($fila) : null;
  }

  

    public function insert(Entidad $fila): bool {
    $propiedades = $fila->toArray();
    $columnas = array_keys($propiedades);

    $columnasSinÑ = array_map(fn($c) => str_replace("ñ", "n", $c), $columnas);

    $sql = "INSERT INTO {$this->tabla} ";
    $sql.= "(" . implode(", ", $columnas) . ") ";
    $sql.= "VALUES (:" . implode(", :",$columnasSinÑ) . ")";
    

    $stmt = $this->cbd->prepare($sql);
    foreach( $propiedades as $columna => $valor ) {
      $columnaSinÑ = str_replace("ñ","n",$columna);
      $stmt->bindValue(":$columnaSinÑ", $valor);
    }
    return $stmt->execute();
  }

  public function update(mixed $id, mixed $fila) : bool {
    $propiedades = $fila->toArray();
    $sql = "UPDATE {$this->tabla} ";
    $columnas = array_map(fn($columna):string => "$columna = :$columna", array_keys($propiedades));

    $sql.= "SET " . implode(", ", $columnas);

    $stmt = $this->cbd->prepare($sql);
    
    foreach ($propiedades as $columna => $value) {
      $stmt->bindValue(":$columna", $value);
    }

    $stmt->bindValue("pk_{$this->clavePrimaria}", $id);
    return $stmt->execute();
  }

  public function delete(mixed $id) : bool {
    $sql = "DELETE FROM {$this->tabla} ";
    $sql.= "WHERE {$this->clavePrimaria} = :id";

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":id", $id);
    return $stmt->execute();
  }








}
