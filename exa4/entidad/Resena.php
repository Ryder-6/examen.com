<?php

namespace exa4\entidad;

use DateTime;
use JsonSerializable;

class Resena implements JsonSerializable
{
  private int $id_reseña;
  private string $nif;
  private string $referencia;
  private \DateTime $fecha;
  private int $clasificacion;
  private string $comentario;

  private const string F_FECHA = "d-m-y";

  public function __construct(array $reseña)
  {
    foreach ($reseña as $propiedad => $value) {
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
    $datos['fecha'] =  $datos['fecha']->format(self::F_FECHA);

    return $datos;
  }
}
