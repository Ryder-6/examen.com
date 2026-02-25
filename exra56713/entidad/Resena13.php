<?php

namespace exra56713\entidad;

use JsonSerializable;
use DateTime;

class Resena13 implements JsonSerializable
{

  protected int $id_reseña;
  protected ?string $nif;
  protected ?string $referencia;
  protected DateTime $fecha;
  protected int $clasificacion;
  protected ?string $comentario;

  private string $F_FECHA = "d-m-Y";

  public function __construct(array $datos)
  {
    foreach ($datos as $propiedad => $value) {
      $this->__set($propiedad, $value);
    }
  }

  public function __set(string $propiedad, string $value)
  {
    try {
      //code...
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
    } catch (\Exception $e) {
      header('conten-type:application/json;charset=utf-8');
      http_response_code(402);
      echo json_encode($e->getMessage());
    }
  }

  public function jsonSerialize(): mixed
  {


    $datos = get_object_vars($this);
    $datos['fecha'] = $datos['fecha']->format($this->F_FECHA);
    
    return $datos;
  }
}
