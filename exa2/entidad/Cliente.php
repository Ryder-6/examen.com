<?php

namespace exa2\entidad;

use JsonSerializable;


class Cliente implements JsonSerializable
{
  protected string $nif;
  protected string $nombre;
  protected string $apellidos;
  protected string $clave;
  protected string $iban;
  protected ?string $telefono;
  protected string $email;
  protected ?float $ventas;

  public function __construct(array $datos)
  {
    foreach ($datos as $propiedad => $value) {
      $this->__set($propiedad, $value);
    }
  }

  public function __set($propiedad, $value)
  {
    if (property_exists($this, $propiedad)) {
      switch ($propiedad) {
        case 'ventas':
          $this->$propiedad = floatval($value);
          break;

        default:
          $this->$propiedad = $value;
          break;
      }
    }
  }



  public function jsonSerialize(): mixed
  {
    foreach ($this as $propiedad => $value) {
      $datos[$propiedad] = $value;
    }
    return $datos;
  }
}?>
