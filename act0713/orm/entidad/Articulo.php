<?php

namespace act0713\orm\entidad;


class Articulo extends Entidad {
  protected string $referencia;
  protected string $descripcion;
  protected float $pvp;
  protected ?float $dto_venta;
  protected ?int $und_vendidas;
  protected ?int $und_disponibles;
  protected ?\DateTime $fecha_disponible;
  protected string $categoria;
  protected ?string $tipo_iva;

  public static function getTipos(): array
  {
    return [
      'referencia' => 'string',
      'descripcion' => 'string',
      'pvp' => 'float',
      'dto_venta' => 'float',
      'und_vendidas' => 'int',
      'und_disponibles' => 'int',
      'fecha_disponible' => \DateTime::class,
      'categoria' => 'string',
      'tipo_via' => 'string'
    ];
  }
}


?>