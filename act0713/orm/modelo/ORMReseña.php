<?php
namespace act0713\orm\modelo;

use act0713\orm\entidad\Reseña;

class ORMReseña extends ORMBase 
{
  protected string $tabla = 'reseña';
  protected string $clavePrimaria = 'id_reseña';

  public function getClaseEntidad(): string
  {
    return Reseña::class; 
  }
}

?>