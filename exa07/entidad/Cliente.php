<?php

namespace exa07\entidad;

use JsonSerializable;

class Cliente implements JsonSerializable{
  protected string $nif;
  protected string $nombre;
  protected string $apellido;
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

  public function __set(string $propiedad, mixed $value)
  {
    if (property_exists($this, $propiedad)) {
      switch ($propiedad) {
        case 'nif':
        case 'nombre':
        case 'apellido':
        case 'clave':
        case 'iban':
        case 'telefono':
        case 'email':
          $this->$propiedad = $value;
          break;
        case 'float':
          $this->$propiedad = floatval($value);
          break;
        
        default:
          # code...
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
}

?>