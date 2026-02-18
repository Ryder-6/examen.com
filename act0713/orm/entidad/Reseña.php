<?php

namespace act0713\orm\entidad;

class Reseña extends Entidad
{
  protected ?int $id_reseña;
  protected string $nif;
  protected string $referencia;
  protected \DateTime $fecha;
  protected int $clasificacion;
  protected string $comentario;

  public static function getTipos(): array
  {
    return [
      'id_reseña' => 'int',
      'nif' => 'string',
      'referencia' => 'string',
      'fecha' => \DateTime::class,
      'clasificacion' => 'int',
      'comentario' => 'string'
    ];
  }
}
