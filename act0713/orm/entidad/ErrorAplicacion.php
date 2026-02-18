<?php

namespace act0713\orm\entidad;

use Exception;

class ErrorAplicacion extends Exception {
  protected array $puntoRecuperacion;

  public function __construct(string $mensaje, int $codigo, array $pr) {
    parent::__construct($mensaje, $codigo, null);
    $this->puntoRecuperacion = $pr;
  }

  public function getPuntoRecuperacion(): array {
    return $this->puntoRecuperacion;
  }
}



?>