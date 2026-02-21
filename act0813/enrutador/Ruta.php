<?php

namespace act0813\enrutador;

class Ruta {
  protected string $metodoHTTP;
  protected string $expRegURI;
  protected string $claseModelo;
  protected string $metodo;
  protected bool $requiereAuth;
  protected array $perfiles;

  public function __construct(string $metodoHTTP, 
                              string $expRegURI,
                              string $claseModelo,
                              string $metodo,
                              bool $requiereAuth = false,
                              array $perfiles = ['user']) {

    $this->metodoHTTP = $metodoHTTP;
    $this->expRegURI = $expRegURI;
    $this->claseModelo = $claseModelo;
    $this->metodo = $metodo;
    $this->requiereAuth = $requiereAuth;
    $this->perfiles = $perfiles;
  }

  public function esIgual(string $metodoHTTP, string $uriPeticion): bool {
    return $this->metodoHTTP === $metodoHTTP && 
      preg_match($this->expRegURI, $uriPeticion);
  }

  public function getRequiereAuth(): bool {
    return $this->requiereAuth;
  }

  public function getPerfiles(): array {
    return $this->perfiles;
  }

  public function getClase(): string {
    return $this->claseModelo;
  }

  public function getMetodo(): string {
    return $this->metodo;
  }
  
  public function getExpRegURI(): string {
    return $this->expRegURI;
  }

  public function getMetodoHTTP(): string {
    return $this->metodoHTTP;
  }
}

?>