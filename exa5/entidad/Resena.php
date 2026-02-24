<?php

namespace exa5\entidad;

use DateTime;
use JsonSerializable;

class Resena implements JsonSerializable
{
  protected int $id_reseña;
  protected string $nif;
  protected string $referencia;
  protected DateTime $fecha;
  protected int $clasificacion;
  protected string $comentario;

  public function __construct(array $datos)
  {
    foreach ($datos as $propiedad => $value) {
      $this->__set($propiedad, $value);
    }
  }

  public function __set(string $propiedad, mixed $value)
  {
    if (property_exists($this, $propiedad)) {
      switch ($propiedad) {
        case 'id_reseña':
          $this->$propiedad = intval($value);
          break;
        case 'nif':
          $this->$propiedad = $value;
          break;
        case 'referencia':
          $this->$propiedad = $value;
          break;
        case 'fecha':
          $this->$propiedad = $value instanceof DateTime ? $value : new DateTime($value);
          break;
        case 'clasificacion':
          $this->$propiedad = intval($value);
          break;
        case 'comentario':
          $this->$propiedad = $value;
          break;
      }
    }
  }

  public function jsonSerialize(): mixed
  {
    $datos = get_object_vars($this);
    $datos['fecha'] = $datos['fecha']->format('d-m-Y');
    return $datos;
  }
}
