<?php

namespace exa\entidad;

use DateTime;
use JsonSerializable;

class Articulo
{
  protected string $referencia;
  protected string $descipcion;
  protected float $pvp;
  protected ?float $dto_venta;
  protected ?float $und_vendidas;
  protected ?float $und_disponibles;
  protected ?DateTime $fecha_disponible;
  protected string $categoria;
  protected string $tipo_via;


  public function __construct()
  {
    throw new \Exception('Not implemented');
  }

  public function __set($propiedad, $value)
  {
    if (property_exists($this, $propiedad)) {
      switch ($propiedad) {
        case 'und_vendidas':
        case 'und_disponibles':
          $this->$propiedad = intval($value);
          break;

        case 'pvp':
        case 'dto_venta':
          $this->$propiedad = floatval($value);
          break;
        case 'fecha':
          if ($value instanceof DateTime) {
            $this->$propiedad = $value;
          }
          if (gettype($value) == 'string') {
            $this->$propiedad = new DateTime($value);
          }

        default:
          $this->$propiedad = $value;
          break;
      }
    }
  }

  public function JsonSerializable()
  {
    foreach ($this as $propiedad => $value) {
    $datos[$propiedad] = $value;
    }
    return $datos;
  }
}
