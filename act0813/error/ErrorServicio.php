<?php

namespace act0813\error;


class ErrorServicio extends \Exception
{
  public const ERROR_401 = "1401";
  public const ERROR_403 = "1403";
  public const ERROR_404 = "1404";
  public const ERROR_405 = "1405";
  public const ERROR_422 = "1422";
  public const ERROR_500 = "1500";

  protected int $estado;
  protected string $codigoError;
  protected string $mensajeError;

  public function __construct(int $estado, string $codigoError, string $mensajeError) {
    $this->estado = $estado;
    $this->codigoError = $codigoError;
    $this->mensajeError = $mensajeError;
  }

  public function getEstado(): int {
    return $this->estado;
  }

  public function getCodigoError() : string {
    return $this->codigoError;
  }

  public function getMensajeError() : string {
    return $this->mensajeError;
  }
}


?>