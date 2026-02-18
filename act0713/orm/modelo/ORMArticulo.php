<?php

namespace act0713\orm\modelo;

use act0713\orm\entidad\Articulo;

class ORMArticulo extends ORMBase
{
  protected string $tabla = 'articulo';
  protected string $clavePrimaria = 'referencia';

  public function getClaseEntidad(): string
  {
    return Articulo::class;
  }

  public function getOfertas(float $nPorcent): array
  {
    $sql = <<<SQL
      SELECT * FROM {$this->tabla}
      WHERE dto_venta >= :nPorcent
      ORDER BY dto_venta DESC
    SQL;

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":nPorcent", $nPorcent);
    $filas = [];
    $claseEntidad = $this->getClaseEntidad();

    if ($stmt->execute()) {
      while ($fila = $stmt->fetch()) {
        $filas[] = new $claseEntidad($fila);
      }
    }

    return $filas;
  }

  public function getMasVendidos(int $n): array
  {
    $sql = <<<SQL
      SELECT * FROM {$this->tabla}
      ORDER BY und_vendidas DESC
      LIMIT :n
    SQL;

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":n", $n);
    $filas = [];
    $claseEntidad = $this->getClaseEntidad();

    if ($stmt->execute()) {
      while ($fila = $stmt->fetch()) {
        $filas[] = new $claseEntidad($fila);
      }
    }

    return $filas;
  }

  public function ultimasCompras(string $nif) : array {
    $sql = <<<SQL
      SELECT npedido, fecha, referencia, descripcion, unidades, precio, dto  
      FROM pedido 
      INNER JOIN lpedido USING(npedido) 
      INNER JOIN articulo USING(referencia) 
      WHERE nif = :nif;
    SQL;

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":nif", $nif);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function haComprado(string $nif, string $referencia) : bool  {
    $sql = <<<SQL
      SELECT nif, referencia 
      FROM pedida
      INNER JOIN lpedido USING(npedido)
      WHERE nif = :nif AND referencia = :referencia
    SQL;

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":nif", $nif);
    $stmt->bindValue(":referencia", $referencia);
    $stmt->execute();
    $filas = count($stmt->fetchAll());

    return $filas >0;
  }



}
