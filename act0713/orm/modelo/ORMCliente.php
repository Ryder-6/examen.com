<?php

namespace act0713\orm\modelo;

use act0713\orm\entidad\Cliente;
use act0713\orm\entidad\ErrorAplicacion;


class ORMCliente extends ORMBase
{
  protected string $tabla = "cliente";
  protected string $clavePrimaria = 'nif';

  public function getClaseEntidad(): string
  {
    return Cliente::class;
  }

  public function autenticar(string $email, string $clave): ?Cliente
  {
    $sql = "SELECT * FROM {$this->tabla} WHERE email = :email";

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":email", $email);
    if ($stmt->execute()) {
      $fila = $stmt->fetch();
      if ($fila) {
        if (password_verify($clave, $fila['clave'])) {
          $claseEntidad = $this->getClaseEntidad();
          $cliente = new $claseEntidad($fila);
          return $cliente;
        }
      }
    }
    return null;
  }
}
