<?php

namespace act0813\orm\entidad;

use DateTime;
use JsonSerializable;

abstract class Entidad implements JsonSerializable
{

  public static abstract function getTipos(): array;

  //Constantes formatos fecha
  public const FORMATO_FECHA_MYSQL = "Y-m-d G:i:s";
  public const FORMATO_FECHA = "d/m/Y";
  public const FORMATO_FECHA_HORA = "d/m/Y G:i:s";


  public function __construct(array $datos)
  {
    foreach ($datos as $columna => $value) {
      $this->__set($columna, $value);
    }
  }


  public function __get(string $propiedad)
  {
    if (property_exists($this, $propiedad)) {
      return $this->$propiedad;
    }
    return null;
  }

  public function __set(string $propiedad, mixed $value)
  {
    if (property_exists($this, $propiedad)) {
      $this->$propiedad = $this->__cast($propiedad, $value);
    }
  }

  private function __cast(string $propiedad, mixed $value): mixed
  {
    $tiposDatos = static::getTipos();
    $tipoPropiedad = $tiposDatos[$propiedad];

    if ($value === null) return null;

    switch ($tipoPropiedad) {
      case 'int':
        $v = (int)$value;
        break;
      case 'float':
        $v = (float)$value;

        break;
      case 'bool':
        $v = (bool)$value;

        break;
      case 'string':
        $v = (string)$value;
        break;
      case \DateTime::class:
        $v = $value instanceof \DateTime ?  $value : new DateTime($value);
        break;
      default:
        throw new \TypeError("El tipo de datos de $value en la propiedad $propiedad no es correcto", 1);
    }

    return $v;
  }


  public function toArray(): array
  {
    $propiedades = get_object_vars($this);
    $infoTipos = static::getTipos();

    foreach ($propiedades as $columna => $value) {
      switch ($infoTipos[$columna]) {
        case 'int':
        case 'float':
        case 'bool':
        case 'string':
          $v = $value;
          break;
        case \DateTime::class:
          $v = $value ? $value->format(self::FORMATO_FECHA_MYSQL) : $value;
          break;
      }

      $columnas[$columna] = $v;
    }
    return $columnas;
  }

  public function __serialize(): array
  {
    return $this->toArray();
  }
  
  public function __unserialize(array $datos): void
  {
    foreach ($datos as $propiedad => $value) {
      $this->__set($propiedad, $value);
    }
  }

  public function jsonSerialize(): mixed
  {
    return $this->toArray();
  }
}
